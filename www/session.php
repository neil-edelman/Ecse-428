<?php

	class Session {

		const SERVER   = "127.0.0.1"; /* :3306? not working */
		const USERNAME = "payomca_rms";
		const PASSWORD = "mushroom";
		const DATABASE = "payomca_rms";
		/* 60s/m * 60m/h * 12h (seconds); yum yum yum */
		const COOKIE_TIME = 43200;
		/* (seconds) */
		const SESSION_TIME = 5; /* <- change */

		/* prevents multiple sessions being created */
		private static $session = null;

		private $db     = null;
		private $status = "okay";
		/* when destroy_session() sets $active = false but the session is still
		 active because destroy_session() doesn't do like it says exactly */
		private $active = true;

		/** session_start is called idempotently at the BEGINNING;
		 you should not call session_start() (it's done already)
		 @author Neil */
		public function __construct() {
			self::$session and throw_exception("Sessions are idempotent.");
			self::$session = $this;
			session_set_cookie_params(self::COOKIE_TIME/*, "/", "payom.ca" <- final */);
			session_start();
			// it barfs -- $utc = new DateTimeZone("UTC");
		}

		/** database? close, etc
		 @author Neil */
		public function __destruct() {
			$this->db && $this->db->close();
			self::$session = null;
		}

		/** @author Neil */
		public function __toString() {
			return "Session ".session_id()." ".($this->db ? "dis" : "")
				."connected.";
		}

		/* @return the link to the database or null (@see get_status for error)
		 @author Neil */
		final public function link_database() {

			if($this->db) return $this->db;

			$db = new mysqli(self::SERVER, self::USERNAME, self::PASSWORD, self::DATABASE);
			if($db->connect_errno) {
				$this->status = "link_database connect failed: (".$db->connect_errno.") ".$db->connect_error;
				$db->close();
				return null;
			}
			/* sane TZ! don't have to worry about DST for now() */
			$db->query("SET time_zone='+0:00'");

			$this->db = $db;

			return $db;
		}

		/** searches for a user with the session_id
		 @return the logged in user or null
		 @author Neil */
		final public function get_user() {

			if(!($db = $this->db) || !$this->active) {
				$this->status = "get_user: database connection closed";
				return null;
			}

			$logged = null;

			/* seach the session table for the local session */
			try {
				$stmt = $db->prepare("SELECT session_id, ip, activity, username "
									 ."FROM SessionID "
									 ."WHERE session_id = ? "
									 ."LIMIT 1") or throw_exception("prepare");
				$stmt->bind_param("s", session_id()) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$stmt->bind_result($session_id, $ip, $activity, $username);
				/* is there record of it on the server? */
				if($stmt->fetch()) {
					/* is the ip address the same? somethings shady if it isn't */
					$ip == $_SERVER["REMOTE_ADDR"] or throw_exception("ip");

					/* fixme: clean up all extraneous sessions */

					/* if the user has been active */
					$utc  = new DateTimeZone("UTC");
					$last = new DateTime($activity, $utc);
					$now  = new DateTime(gmdate("Y-m-d H:i:s"), $utc);
					$diff = $now->getTimestamp() - $last->getTimestamp();
					/* fixme: this is awful */
					if($diff > self::SESSION_TIME) {
						$this->status = $diff."s timeout";
						$stmt->close(); /* no nesting stmt */
						$this->logoff();
						/* this prevents the user from logging on automatically
						 since the destroy_session only marks the session for
						 deletion */
						$this->active = false;
						return null;
					}

					$logged = $username;
				}
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "get_user ".$e->getMessage()." failed: (".$errno.") ".$error;
			}
			$stmt and $stmt->close();
			
			if(!$logged) return null;
			
			/* update the active time to now */
			try {
				$stmt = $db->prepare("UPDATE SessionID SET activity = now() "
									 ."WHERE session_id = ? "
									 ."LIMIT 1") or throw_exception("prepare");
				$stmt->bind_param("s", session_id()) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "is_logged_in ".$e->getMessage()." update time failed: (".$errno.") ".$error;
			}
			$stmt and $stmt->close();

			return $logged;
		}

		/** log in
		 @param user password
		 @param pass username
		 @return the user who is logged in (can be null)
		 @author Neil */
		final public function login($user, $pass) {

			if(!($db = $this->db)) {
				$this->status = "login: database connection closed";
				return null;
			}
			/* preserve status when called in get_user() */
			if(!$this->active) return null;

			/* does the user exist in the database and the password match? */
			$loggedin = null;
			try {
				$stmt = $db->prepare("SELECT password FROM "
									 ."Users WHERE username = ? "
									 ."LIMIT 1") or throw_exception("prepare");
				$stmt->bind_param("s", $user) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$stmt->bind_result($server_pass);
				if($stmt->fetch() && $this->password_verify($pass, $server_pass)) {
					$loggedin = $user;
				} else {
					$this->status = "invalid authorisation";
				}
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "login search ".$e->getMessage()." failed: (".$errno.") ".$error;
			}
			$stmt and $stmt->close();

			if(!$loggedin) return null;

			/* store the session - local version */
			$session                          = session_id();
			$_SESSION["username"]             = $user;
			$ip       = $_SESSION["ip"]       = $_SERVER["REMOTE_ADDR"];
			$activity = $_SESSION["activity"] = gmdate("Y-m-d H:i:s");
			
			/* store on the server */
			$loggedin = null;
			try {
				$stmt = $db->prepare("INSERT INTO "
									 ."SessionID(session_id, username, ip, activity)"
									 ." VALUES (?, ?, ?, ?)") or throw_exception("prepare");
				$stmt->bind_param("ssss", $session, $user, $ip, $activity) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$loggedin = $user;
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "login store ".$e->getMessage()." failed: (".$errno.") ".$error;
			}
			$stmt and $stmt->close();

			return $loggedin;
		}

		/** logs you out of your session
		 @return whether you have been logged out
		 @author Neil */
		final public function logoff() {

			if(!($db = $this->db) || !$this->active) {
				$this->status = "logoff: database connection closed";
				return false;
			}

			$success = false;
			try {
				$stmt = $db->prepare("DELETE FROM "
									 ."SessionID WHERE session_id = ? "
									 ."LIMIT 1") or throw_exception("prepare");
				$stmt->bind_param("s", session_id()) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$success = true;
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "logoff ".$e->getMessage()." failed: (".$errno.") ".$error;
			}
			$stmt and $stmt->close();

			$success and session_destroy();

			return $success;
		}

		/** new user? assumes valid input and access (this will have to be updated)
		 @param user username
		 @param pass password
		 @param first first name
		 @param last last name
		 @return the username created or null
		 @author Neil */
		final public function new_user($user, $pass, $first, $last) {

			if(!($db = $this->db) || !$this->active) {
				$this->status = "new_user: database connection closed";
				return null;
			}

			$created = null;
			try {
				$stmt = $db->prepare("INSERT INTO "
									 ."Users(username, password, FirstName, LastName) "
									 ."VALUES (?, ?, ?, ?)") or throw_exception("prepare");
				$stmt->bind_param("ssss", $user, $pass, $first, $last) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$created = $user;
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "new_user ".$e->getMessage()." failed: (".$errno.") ".$error;
			}
			$stmt and $stmt->close();

			return $created;
		}

		/** gets the info associated with a user (or null)
		 @return the user info as an associative array
		 @author Neil */
		final public function user_info($user) {

			if(!($db = $this->db) || !$this->active) {
				$this->status = "user_info: database connection closed";
				return null;
			}

			$info = null;
			try {
				$stmt = $db->prepare("SELECT username, password, FirstName, LastName, Privilege FROM "
									 ."Users WHERE username = ? "
									 ."LIMIT 1") or throw_exception("prepare");
				$stmt->bind_param("s", $user) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$stmt->bind_result($username, $password, $FirstName, $LastName, $Privilege);
				if($stmt->fetch()) {
					$info["username"]  = $username;
					$info["password"]  = $password;
					$info["FirstName"] = $FirstName;
					$info["LastName"]  = $LastName;
					$info["Privilege"] = $Privilege;
				} else {
					$this->status = "not a user";
				}
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "user_info ".$e->getMessage()." failed: (".$errno.") ".$error;
			}

			return $info;
		}

		/** this is an alias of versions > 5.3
		 @param plain plain pswd
		 @return crypt pswd
		 @author Neil */
		final private static function password_hash($plain) {
			$salt = bin2hex(openssl_random_pseudo_bytes(22, $isCrypto));
			$isCrypto or die("No cryptography on this server.");
			/* Blowfish: "$2a" + "$xx" xx=number of iterations + "$" + 22chars */
			return crypt($plain, "$2a$07$".$salt);
		}
		
		/** this is an alias of versions > 5.3
		 @param plain the unhashed password
		 @param hash the hashed password (viz on the server)
		 @return whether the password is valid
		 @author Neil */
		final private static function password_verify($plain, $hash) {		
			return crypt($plain, $hash) == $hash;
		}

		/** ohnoz1! something has failed; get_status()
		 @return why (hopefully)
		 @author Neil */
		final public function status() {
			return $this->status;
		}

	}

	/** returns whether your allowed to make changes to stuff
	 @param info the assoc array returned from user_info
	 @return true/false
	 @author Neil */
	function is_admin($info) {
		return $info["Privilege"] == "admin" || $info["Privilege"] == "manager";
	}

	/** create new exception; this is sytactic sugar
	 @param message message (defualt null)
	 @param code the error code (default null)
	 @author Neil */
	function throw_exception($message = null, $code = null) {
		throw new Exception($message, $code);
	}

	/** redirect to index.php on error
	 @param message message (defualt null)
	 @author Neil */
	function header_exception($message = null) {
		header("Location: index.php?message=Error+".urlencode($message));
		exit();
	}

?>

<?php

	class Session {

		const SERVER   = "127.0.0.1"; /* :3306? not working */
		const USERNAME = "payomca_rms";
		const PASSWORD = "mushroom";
		const DATABASE = "payomca_rms";
		/* 60s/m * 60m/h * 12h (seconds); yum yum yum */
		const COOKIE_TIME = 43200;

		private static $session = null;

		private $status = "okay";

		private $db;

		/** session_start is called idempotently at the BEGINNING;
		 you should not call session_start() (it's done already)
		 @author Neil */
		public function __construct() {
			self::$session and throw_exception("Sessions are idempotent.");
			self::$session = $this;
			/* probably should do this: set_error_handler("error_handler");*/
			session_set_cookie_params(self::COOKIE_TIME/*, "/", "payom.ca" <- final */);
			session_start();
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
			/* sane TZ! don't have to worry about DST */
			$db->query("SET time_zone='+0:00'");

			$this->db = $db;

			return $db;
		}

		/** @return the logged in user or null; fixme: timeout
		 @author Neil */
		final public function get_user() {

			if(!($db = $this->db)) {
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
					/* fixme!!! */
					/* if the user has been active */
					/* working with datetimes is SO ANNOYING AND DEOSN'T WORK AT ALL */
					/* fixme: clean up all the other sessions */
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
		 @author Neil */
		final public function login($user, $pass) {

			if(!($db = $this->db)) {
				$this->status = "login: database connection closed";
				return null;
			}

			/* does the user exist in the database and the password match? */
			$return = false;
			try {
				$stmt = $db->prepare("SELECT password FROM "
									 ."Users WHERE username = ? "
									 ."LIMIT 1") or throw_exception("prepare");
				$stmt->bind_param("s", $user) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$stmt->bind_result($server_pass);
				if($stmt->fetch() && $this->password_verify($pass, $server_pass)) {
					$return = true;
				} else {
					$this->status = "invalid authorisation";
				}
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "login search ".$e->getMessage()." failed: (".$errno.") ".$error;
			}
			$stmt and $stmt->close();
			
			if(!$return) return false;
			
			/* store the session - local version */
			$session                          = session_id();
			$_SESSION["username"]             = $user;
			$ip       = $_SESSION["ip"]       = $_SERVER['REMOTE_ADDR'];
			$activity = $_SESSION["activity"] = gmdate("Y-m-d H:i:s");
			
			/* store on the server */
			$return = false;
			try {
				$stmt = $db->prepare("INSERT INTO "
									 ."SessionID(session_id, username, ip, activity)"
									 ." VALUES (?, ?, ?, ?)") or throw_exception("prepare");
				$stmt->bind_param("ssss", $session, $user, $ip, $activity) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$return = true;
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "login store ".$e->getMessage()." failed: (".$errno.") ".$error;
			}
			$stmt and $stmt->close();
			
			return $return;
		}

		/** logs you out of your session
		 @return whether you have been logged out
		 @author Neil */
		final public function logoff() {

			if(!($db = $this->db)) {
				$this->status = "logoff: database connection closed";
				return false;
			}

			$return = false;
			try {
				$stmt = $db->prepare("DELETE FROM "
									 ."SessionID WHERE session_id = ? "
									 ."LIMIT 1") or throw_exception("prepare");
				$stmt->bind_param("s", session_id()) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$return = true;
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "logoff ".$e->getMessage()." failed: (".$errno.") ".$error;
			}
			$stmt and $stmt->close();

			$return and session_destroy();

			return $return;
		}

		/** new user? assumes valid input and access
		 @param user username
		 @param pass password
		 @param first first name
		 @param last last name
		 @return true/false wheater the user was created
		 @author Neil */
		final public function new_user($user, $pass, $first, $last) {

			if(!($db = $this->db)) {
				$this->status = "logoff: database connection closed";
				return false;
			}

			$return = false;
			try {
				$stmt = $db->prepare("INSERT INTO "
									 ."Users(username, password, FirstName, LastName) "
									 ."VALUES (?, ?, ?, ?)") or throw_exception("prepare");
				$stmt->bind_param("ssss", $user, $pass, $first, $last) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$return = true;
			} catch(Exception $e) {
				$errno = ($stmt ? $stmt->errno : $db->errno);
				$error = ($stmt ? $stmt->error : $db->error);
				$this->status = "new_user ".$e->getMessage()." failed: (".$errno.") ".$error;
			}
			$stmt and $stmt->close();
			return $return;
		}

		/** this is a alias of versions > 5.3
		 @param plain plain pswd
		 @return crypt pswd
		 @author Neil */
		final private static function password_hash($plain) {
			$salt = bin2hex(openssl_random_pseudo_bytes(22, $isCrypto));
			$isCrypto or die("No cryptography on this server.");
			/* Blowfish: "$2a" + "$xx" xx=number of iterations + "$" + 22chars */
			return crypt($plain, "$2a$07$".$salt);
		}
		
		/** this is a alias of versions > 5.3
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

		/** create new exception; this is sytactic sugar
		 @param message message (defualt null)
		 @param code the error code (default null)
		 @author Neil */
		final private static function throw_exception($message = null, $code = null) {
			throw new Exception($message, $code);
		}

	}

?>

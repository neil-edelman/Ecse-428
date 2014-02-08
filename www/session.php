<?php

	/* database things */
	//const COOKIE_TIME = 43200; /* 60s/m * 60m/h * 12h (seconds) */
	define("COOKIE_TIME", 43200); /* yum yum yum */

	/** create new exception */
	function throw_exception($message = null, $code = null) {
		throw new Exception($message, $code);
	}

	/** called first */
	function persistent_session_start() {
		/* probably should do this: set_error_handler("error_handler");*/
		session_set_cookie_params(COOKIE_TIME/*, "/", "payom.ca" <- final */);
		session_start();
	}
	
	/** returns the logged in user or null; fixme: timeout */
	function get_logged_in_user($db) {

		$logged = null;

		/* seach the session table for the local session */
		try {
			$stmt = $db->prepare("SELECT session_id, ip, activity, username "
								 ."FROM SessionID "
								 ."WHERE session_id = ? "
								 ."LIMIT 1") or throw_exception("prepare");
			try {
				$stmt->bind_param("s", session_id()) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
				$stmt->bind_result($session_id, $ip, $activity, $username);
				/* there is no record of it on the server; the user hasn't logged in */
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
				echo "is_logged_in ".$e->getMessage()." failed: (".$stmt->errno.") ".$stmt->error;
			}
			$stmt->close();
		} catch(Exception $e) {
			echo "is_logged_in ".$e->getMessage()." failed: (".$db->errno.") ".$db->error;
		}

		/* we are not logged in */
		if(!$logged) return null;

		/* update the active time to now */
		try {
			$stmt = $db->prepare("UPDATE SessionID SET activity = now() "
								 ."WHERE session_id = ? "
								 ."LIMIT 1") or throw_exception("prepare");
			try {
				$stmt->bind_param("s", session_id()) or throw_exception("binding");
				$stmt->execute() or throw_exception("execute");
			} catch(Exception $e) {
				echo "is_logged_in ".$e->getMessage()." update time failed: (".$stmt->errno.") ".$stmt->error;
			}
			$stmt->close();
		} catch(Exception $e) {
			echo "is_logged_in ".$e->getMessage()." update time failed: (".$db->errno.") ".$db->error;
		}

		return $logged;
	}

	/** log in */
	function login($db, $user, $pass) {

		/* does the user exist in the database */
		if(!($stmt = $db->prepare("SELECT password FROM "
								  ."Users WHERE username = ? LIMIT 1"))) {
			echo "login.1 prepare failed: (".$db->errno.") ".$db->error;
			return false;
		}
		if(!$stmt->bind_param("s", $user)) {
			echo "login.1 binding failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		if(!$stmt->execute()) {
			echo "login.1 execute failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		$stmt->bind_result($server_pass);
		if(!$stmt->fetch()) {
			echo "login.1 fetching failed: (".$db->errno.") ".$db->error;
			$stmt->close();
			return false;
		}
		/* there is no record of it on the server; the user hasn't logged in */
		if(!password_verify($pass, $server_pass)) {
			$stmt->close();
			return false;
		}
		$stmt->close();

		/* store the session - local version */
		$session                          = session_id();
		$_SESSION["username"]             = $user;
		$ip       = $_SESSION["ip"]       = $_SERVER['REMOTE_ADDR'];
		$activity = $_SESSION["activity"] = gmdate("Y-m-d H:i:s");

		/* store on the server */
		if(!($stmt = $db->prepare("INSERT INTO "
								  ."SessionID(session_id, username, ip, activity)"
								  ." VALUES (?, ?, ?, ?)"))) {
			echo "login.2 prepare failed: (".$db->errno.") ".$db->error;
			return false;
		}
		if(!$stmt->bind_param("ssss", $session, $user, $ip, $activity)) {
			echo "login.2 binding failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		if(!$stmt->execute()) {
			echo "login.2 execute failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		$stmt->close();

		return true;
	}

	/** logs you out of your session */
	function logoff($db) {
		if(!($stmt = $db->prepare("DELETE FROM "
								  ."SessionID WHERE session_id = ? LIMIT 1"))) {
			echo "logoff prepare failed: (".$db->errno.") ".$db->error;
			return false;
		}
		if(!$stmt->bind_param("s", session_id())) {
			echo "logoff binding failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		if(!$stmt->execute()) {
			echo "logoff execute failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		$stmt->close();

		session_destroy();

		return true;
	}

	/** you will have to $db->close() */
	function link_database() {

		/* :3306? not working */
		$db = new mysqli("127.0.0.1", "payomca_rms", "mushroom", "payomca_rms");		
		if($db->connect_errno) {
			die("link_database connect failed: (".$db->connect_errno.") ".$db->connect_error);
		}
		/* sane TZ! don't have to worry about DST */
		$db->query("SET time_zone='+0:00'");

		return $db;
	}

	/** this is a alias of versions > 5.3 */
	function password_hash($plain) {
		$salt = bin2hex(openssl_random_pseudo_bytes(22, $isCrypto));
		if($isCrypto != true) die("No cryptography on this server.");
		/* Blowfish: "$2a" + "$xx" xx=number of iterations + "$" + 22chars */
		return crypt($plain, "$2a$07$".$salt);
	}

	/** this is a alias of versions > 5.3 */
	function password_verify($plain, $hash) {		
		return crypt($plain, $hash) == $hash;
	}

	/** new user? assumes valid input */
	function new_user($db, $user, $pass, $first, $last) {
		if(!($stmt = $db->prepare("INSERT INTO "
								  ."Users(username, password, FirstName, LastName)"
								  ." VALUES (?, ?, ?, ?)"))) {
			echo "new_user prepare failed: (".$db->errno.") ".$db->error;
			return false;
		}
		if(!$stmt->bind_param("ssss", $user, $pass, $first, $last)) {
			echo "new_user binding failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		if(!$stmt->execute()) {
			echo "new_user execute failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		$stmt->close();
		return true;
	}

?>

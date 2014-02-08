<?php

	define("SERVER",      "127.0.0.1");
	define("USERNAME",    "payomca_rms");
	define("PASSWORD",    "mushroom");
	define("DATABASE",    "payomca_rms");
	/* 60s/m * 60m/h * 12h (seconds); yum yum yum */
	define("COOKIE_TIME", 43200);

	/** create new exception; this is sytactic sugar */
	function throw_exception($message = null, $code = null) {
		throw new Exception($message, $code);
	}

	/** called first! */
	function persistent_session_start() {
		error_reporting(E_ALL);
		ini_set("log_errors", 1);
		ini_set("error_log", "/Users/neil/Sites/www/error");
		//ini_set("error_log", "error_log");
		//ini_set("display_errors" , 1);
		/* probably should do this: set_error_handler("error_handler");*/
		session_set_cookie_params(COOKIE_TIME/*, "/", "payom.ca" <- final */);
		session_start();
	}

	/** you will have to $db->close() */
	function link_database() {

		/* :3306? not working */
		$db = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);		
		if($db->connect_errno) {
			die("link_database connect failed: (".$db->connect_errno.") ".$db->connect_error);
		}
		/* sane TZ! don't have to worry about DST */
		$db->query("SET time_zone='+0:00'");

		return $db;
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
			/* fixme: this is not the way to handle; set_error_handler? create a function error()? */
			$errno = ($stmt ? $stmt->errno : $db->errno);
			$error = ($stmt ? $stmt->error : $db->error);
			echo "is_logged_in ".$e->getMessage()." failed: (".$errno.") ".$error;
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
			echo "is_logged_in ".$e->getMessage()." update time failed: (".$errno.") ".$error;
		}
		$stmt and $stmt->close();

		return $logged;
	}

	/** log in */
	function login($db, $user, $pass) {

		/* does the user exist in the database and the password match? */
		$return = false;
		try {
			$stmt = $db->prepare("SELECT password FROM "
								 ."Users WHERE username = ? "
								 ."LIMIT 1") or throw_exception("prepare");
			$stmt->bind_param("s", $user) or throw_exception("binding");
			$stmt->execute() or throw_exception("execute");
			$stmt->bind_result($server_pass);
			if($stmt->fetch() && password_verify($pass, $server_pass)) {
				$return = true;
			}
		} catch(Exception $e) {
			$errno = ($stmt ? $stmt->errno : $db->errno);
			$error = ($stmt ? $stmt->error : $db->error);
			echo "login search ".$e->getMessage()." failed: (".$errno.") ".$error;
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
			echo "login store ".$e->getMessage()." failed: (".$errno.") ".$error;
		}
		$stmt and $stmt->close();

		return $return;
	}

	/** logs you out of your session */
	function logoff($db) {
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
			echo "logoff ".$e->getMessage()." failed: (".$errno.") ".$error;
		}
		$stmt and $stmt->close();

		if($return) session_destroy();

		return $return;
	}

	/** new user? assumes valid input */
	function new_user($db, $user, $pass, $first, $last) {
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
			echo "new_user ".$e->getMessage()." failed: (".$errno.") ".$error;
		}
		$stmt and $stmt->close();
		return $return;
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

?>

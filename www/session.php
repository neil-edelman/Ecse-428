<?php

	/* database things */
	const COOKIE_TIME = 43200; /* 60s/m * 60m/h * 12h (seconds) */

	/** called first */
	function persistent_session_start() {
		session_set_cookie_params(COOKIE_TIME/*, "/", "payom.ca" <- final */);
		session_start();
	}
	
	/** are you logged in? fixme: timeout */
	function is_logged_in($db) {
		/* debug! */
		/*if(!($stmt = $db->prepare("SELECT session_id, ip, activity, username FROM "
								  ."SessionID"))) {
			echo "is_logged_in debug prepare failed: (".$db->errno.") ".$db->error;
			return false;
		}
		if(!$stmt->execute()) {
			echo "is_logged_in debug execute failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		$stmt->store_result();
		$stmt->bind_result($session_id, $ip, $activity, $username);
		while($result = $stmt->fetch()) {
			echo $session_id."; ".$username."<br/>\n";
		}
		$stmt->close();*/

		if(!($stmt = $db->prepare("SELECT session_id, ip, activity, username FROM "
								  ."SessionID WHERE session_id = ? LIMIT 1"))) {
			echo "is_logged_in prepare failed: (".$db->errno.") ".$db->error;
			return false;
		}
		if(!$stmt->bind_param("s", session_id())) {
			echo "is_logged_in binding failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		if(!$stmt->execute()) {
			echo "is_logged_in execute failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return false;
		}
		$stmt->bind_result($session_id, $ip, $activity, $username);
		/* there is no record of it on the server; the user hasn't logged in */
		if(!$stmt->fetch()) {
			$stmt->close();
			echo "is_logged_in didn't find session";
			return false;
		}
		/* is the ip address the same? somethings shady if it isn't */
		if($ip != $_SERVER["REMOTE_ADDR"])  {
			echo "is_logged_in checks failed: the ip address of this "
				 ."connection is different from the one that originally made the session";
			$stmt->close();
			return false;
		}
		/* if the user has been active */
		/* working with datetimes is SO ANNOYING AND DEOSN'T WORK AT ALL */
		/* fixme!!! */
		/*$now = gmdate("Y-m-d H:i:s");
		 if(!isset($_SESSION["activity"])) return false;
		 $stmt = $db->prepare("SELECT TIMESTAMPDIFF(SECOND,now(),?)");
		 if(!$stmt) die("Statement error: (".$db->errno.") ".$db->error);
		 $ok   = $stmt->bind_param("s",
		 $db->escape_string($_SESSION["activity"]));
		 if(!($ok && $stmt->execute())) die("Database error: ".$db->error);
		 $result = $stmt->get_result();
		 echo $result->num_rows;
		 $now = gmdate("Y-m-d H:i:s");
		 
		 $diff = $now - $_SESSION["activity"];
		 echo "now ".$now." then ".$_SESSION["activity"]." diff ".$diff;*/
		
		//		if($diff >= $timeout) {
		//			echo "timeout!".$diff;
		//			$stmt = $db->prepare("DELETE FROM "
		//								 ."session WHERE session_id = ? LIMIT 1");
		//			if(!$stmt) die($db->error);
		//			$ok   = $stmt->bind_param("s",
		//									  $db->escape_string(session_id()));
		//			$stmt->execute(); /* not sure what to do if it fails */
		//			return false;
		//		}
		//		echo "user is active!";
		/* cool; it is valid */
		echo "cool! you are ".$username."<br/>\n";
		echo "sid:".session_id()." db:".$db->server_info;
		$stmt->close();

		/* update the active to now */
		if(!($stmt = $db->prepare("UPDATE SessionID SET activity = now() "
							 ."WHERE session_id = ? LIMIT 1"))) {
			echo "is_logged_in update time prepare failed: (".$db->errno.") ".$db->error;
			return true;
		}
		if(!$stmt->bind_param("s", session_id())) {
			echo "is_logged_in update time binding failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return true;
		}
		if(!$stmt->execute()) {
			echo "is_logged_in update time execute failed: (".$stmt->errno.") ".$stmt->error;
			$stmt->close();
			return true;
		}
		$stmt->close();

		return true;
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

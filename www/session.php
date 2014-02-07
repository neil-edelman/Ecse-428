<?php

	/* database things */
	$user_length  = 64;
	$first_length = 64;
	$last_length  = 64;
	$cookie_time  = 60;//43200; /* 60s/m * 60m/h * 12h (seconds) */

	/** called first */
	function persistent_session_start() {
		session_set_cookie_params($cookie_time/*, "/", "payom.ca" <- final */);
		session_start();
	}
	
	/** are you logged in? */
	function is_logged_in($db) {
		echo "sid:".session_id()." db:".$db->server_info;
		$stmt = $db->prepare("SELECT * FROM "
							 ."session WHERE session_id = ?");
		if(!$stmt) die("Statement error: (".$db->errno.") ".$db->error);
		echo "a";
		$ok   = $stmt->bind_param("s",
								  $db->escape_string(session_id()));
		echo "b";
		if(!($ok && $stmt->execute())) die("Database error: ".$db->error);
		echo "c";
		$stmt->bind_result($colid, $coluser, $colip, $colact);
		if(!$stmt->fetch()) return false;
		//$result = $stmt->get_result();
		//if($result == false) die("Crazy".$db.error);
		echo "d";
		/* there is no record of it on the server (ie the user hasn't logged in) */
		echo "numrows".$result->num_rows."; ";
		if($result->num_rows <= 0) return false;
		/* is the ip address the same? (somethings' shady going on if it isn't) */
		if(!isset($_SESSION['ip']) || $_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) return false;
		echo "the ip is the same! ";
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
		/* update the active to now */
		$stmt = $db->prepare("UPDATE session SET activity = now() "
							 ."WHERE session_id = ? LIMIT 1");
		if(!$stmt) die($db->error);
		$ok   = $stmt->bind_param("s",
								  $db->escape_string(session_id()));
		$stmt->execute(); /* not sure what to do if it fails */
		return true;
	}

	/** log in */
	function login($db, $user, $pass) {
		echo "a";
		$stmt = $db->prepare("SELECT password FROM "
							 ."users WHERE username = ? LIMIT 1");
		echo "b";
		if(!$stmt) die("Statement error: (".$db->errno.") ".$db->error);
		echo "c";
		$ok   = $stmt->bind_param("s",
								  $db->escape_string($user));
		echo "d";
		if(!($ok && $stmt->execute())) die("Database error: ".$db->error);
		echo "e";
		$stmt->bind_result($pass);
		echo "f";
		if(!$stmt->fetch()) return false;
		echo "g";
		//$result = $stmt->get_result();
		$return = false;
		for( ; ; ) {
			echo "x";
			/* there is no record of it on the server;
			 the user hasn't logged in */
			//if($result->num_rows <= 0) break;
			//$entry = $result->fetch_array();
			if(!password_verify($pass, $entry["password"])) break;

			echo "h";
			/* local version */
			$session                          = session_id();
			$_SESSION["username"]             = $user;
			$ip       = $_SESSION["ip"]       = $_SERVER['REMOTE_ADDR'];
			$activity = $_SESSION["activity"] = gmdate("Y-m-d H:i:s");
			
			echo "i";
			/* store on the server */
			$stmt = $db->prepare("INSERT INTO "
								 ."session(session_id, username, ip, activity)"
								 ." VALUES (?, ?, ?, ?)");
			if(!$stmt) die($db->error);
			echo "j";
			$ok   = $stmt->bind_param("ssss",
									  $db->escape_string($session),
									  $db->escape_string($username),
									  $db->escape_string($ip),
									  $db->escape_string($activity));
			echo "k";
			if($ok && $stmt->execute()) {
				$return = true;
			} else {
				echo "Error: ".$db->error;
				$return = false;
			}
			echo "l";
			break;
		}
		//$result->close();
		echo "z";
		return $return;
	}

	/** you will have to $db->close() */
	function link_database() {

		/* :3306? not working */
		$db = new mysqli("127.0.0.1", "payomca_rms", "mushroom", "payomca_rms");		
		if(!$db) {
			die("Connect failed: (".$db->connect_errno.") ".$db->connect_error);
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

	/** logs you out (no checking anything) */
	function logoff($db) {
		$stmt = $db->prepare("DELETE FROM "
							 ."session WHERE session_id = ? LIMIT 1");
		if(!$stmt) die($db->error);
		$ok   = $stmt->bind_param("s",
								  $db->escape_string(session_id()));
		if(!($ok && $stmt->execute())) {
			echo "Error logout: (".$db->errno.") ".$db->error;
			return false;
		}

		session_destroy();

		return true;
	}

	/** new user? assumes valid input */
	function new_user($db, $user, $pass, $first, $last) {
		$stmt = $db->prepare("INSERT INTO "
							 ."users(username, password, firstname, lastname)"
							 ." VALUES (?, ?, ?, ?)");
		if(!$stmt) die($db->error);
		$ok   = $stmt->bind_param("ssss",
								  $db->escape_string($user),
								  $db->escape_string($pass),
								  $db->escape_string($first),
								  $db->escape_string($last));
		if($ok && $stmt->execute()) {
			return true;
		} else {
			echo "Error: ".$db->error;
			return false;
		}
	}

?>

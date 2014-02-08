<?php

	/* database things */
	$user_length  = 64;
	$first_length = 64;
	$last_length  = 64;
	$cookie_time  = 60;//43200; /* 60s/m * 60m/h * 12h (seconds) */

	/** called first */
	function persistent_session_start() {
		ob_start();
		session_set_cookie_params($cookie_time/*, "/", "payom.ca" <- final */);
		session_start();
	}

	/** @depreciated use link_database(); this is a confusing name */
	function db_login() {

		$db = @mysqli_connect("127.0.0.1"/*:3306"*/, "payomca_rms", "mushroom", "payomca_rms");
		if (!$db) {					
			die("Connect failed: " . mysqli_connect_errno());
		}
		return $db;
	}

	/** you will have to $db->close() */
	function link_database() {

		$db = new mysqli("127.0.0.1", "payomca_rms", "mushroom", "payomca_rms");		
		if (!$db) {
			die("Connect failed: (".$db->connect_errno.") ".$db->connect_error);
		}
		/* sane TZ! don't have to worry about DST */
		$db->query("SET time_zone='+0:00'");

		return $db;
	}

	/** this is a alias of versions > 5.3 */
	function password_hash($plain) {
		/* no php 5.5 avilable
		 $password = password_hash($_REQUEST["password"], PASSWORD_DEFAULT); */
		/* mcrypt is not installed
		 $salt = strtr(base64_encode(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)), '+', '.'); */
		/* not cross-platform
		 $fp = fopen('/dev/urandom', 'r');
		 $random = fread($fp, 22);
		 fclose($fp);*/
		/* good enought */
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
	function do_logout($db) {
		$stmt = $db->prepare("DELETE FROM "
							 ."session WHERE session_id = ? LIMIT 1");
		if(!$stmt) die($db->error);
		$ok   = $stmt->bind_param("s",
								  $db->escape_string($session_id()));
		if($ok && $stmt->execute()) {
			header("Location: index.php?message=Loggedoff");
		} else {
			header("Location: index.php?message=NotLoggedoff");//.$db->error;
		}

		session_destroy();
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

	// Returns the username of the currently logged in user. Returns "null" otherwise; as in an actual STRING called "null".
	function check_login() {
		$session_id = session_id();
		$server= mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		$sqlQuery = "SELECT * FROM SessionID WHERE SessionID . session_id = '$session_id';";                        
		$result = mysqli_query($server, $sqlQuery);
		
		if (mysqli_num_rows($result) == 1) {		// Must CHECK if there's one and only row of results
			$theresult = mysqli_fetch_row($result);	// Must GET the one and only row of results
			$loggeduser = $theresult[1];			// Acquire said row's SECOND data field. Ie. the username.
		}
		else {
			$loggeduser = "null";	// Reached if no session is found. 
		}
		
		return $loggeduser;
	}
	
	// Obtains the privilege status of the given username.
	function check_privilege($user) {
		$server= mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		$sqlQuery = "SELECT * FROM Users WHERE Username = '$user';";
		$result = mysqli_query($server, $sqlQuery);
		
		if (mysqli_num_rows($result) == 1) {		// Must CHECK if there's one and only row of results
			$theresult = mysqli_fetch_row($result);	// Must GET the one and only row of results
			$privilege = $theresult[5];		// Acquire said row's FIFTH field. Ie. the privilege.
		}
		else {
			$privilege = "null";
		}
		return $privilege;
	}

?>

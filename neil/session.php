<?php

	$isLoggedIntoDB = false;

	/* database things */
	$user_length  = 64;
	$first_length = 64;
	$last_length  = 64;

	/** you will have to $db->close() */
	function db_login() {
		//$db = new mysqli("127.0.0.1", "public", "12345", "ecse-428")
		$db = new mysqli("127.0.0.1", "payca_rms", "mushroom", "payomca_rms")
			or die("Connect failed: ".$sql->connect_error);
		return $db;
	}

	/** this is not needed for versions > 5.3 */
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

	function password_verify($plain, $hash) {
		return crypt($plain, $hash) == $hash;
	}

	/** checks all the login stuff */
	function check_login($db) {

		$query  = "SELECT * FROM session WHERE session_id='".session_id()."'";
		$result = $db->query($query);

		/* this session exists */
		if($result->num_rows != 1) {
			header("Location: index.php?message=NotLoggedIn");
			return;
		}
		$entry = $db->fetch_array($result);

		/* it's for this ip */
		/* fixme */

		/* it's not expired */
		/* fixme */

		$result->close();

		return -1;
	}

	/** logs you out (no checking anything) */
	function do_logout() {
		session_destroy();
		header("Location: index.php");
	}

	/** new user? assumes valid input */
	function new_user($db, $user, $pass, $first, $last) {
		$stmt = $db->prepare("INSERT INTO "
							 ."users(username, password, first_name, last_name)"
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

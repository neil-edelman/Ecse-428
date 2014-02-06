<?php

	$isLoggedIntoDB = false;

	/* database things */
	$user_length  = 64;
	$first_length = 64;
	$last_length  = 64;

	/** you will have to $db->close()
	 @depreciated use link_database(); it's confusing */
	function db_login() {
			
		$db = @mysqli_connect("127.0.0.1:3306", "payomca_rms", "mushroom", "payomca_rms");		
		if (!$db) {					
			die("Connect failed: " . mysqli_connect_errno());
		}		
		return $db;
	}

	/** you will have to $db->close() */
	function link_database() {

		$db = new mysqli("127.0.0.1:3306", "payomca_rms", "mushroom", "payomca_rms");		
		if (!$db) {
			die("Connect failed: (".$db->connect_errno.") ".$db->connect_error);
		}		
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

?>

<?php
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
		$salt = bin2hex(openssl_random_pseudo_bytes(22, $isCrypto));
		if($isCrypto != true) die("No cryptography on this server.");
		/* Blowfish: "$2a" + "$xx" xx=number of iterations + "$" + 22chars */
		return crypt($plain, "$2a$07$".$salt);
	}

	session_start();

	$db = new mysqli("127.0.0.1", "public", "12345", "ecse-428")
		or die("Connect failed: ".$sql->connect_error);

	$username = strip_tags(stripslashes($db->escape_string($_REQUEST["username"])));
	$password = password_hash($_REQUEST["password"]);
	$first    = strip_tags(stripslashes($db->escape_string($_REQUEST["first_name"])));
	$last     = strip_tags(stripslashes($db->escape_string($_REQUEST["last_name"])));

	/* uhn weird */
	/*$salt = openssl_random_pseudo_bytes(22);
	$salt = '$2a$%13$' . strtr($salt, array('_' => '.', '~' => '/'));*/

	/* good enough */
	/*$salt = uniqid(mt_rand(), true);*/


	/*private $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

	private function base62char($num) {
		return $chars[$num];
	}

	public function random_string($letters) {
		$rand = "";

		for($i = 0; $i < $letters; $i++) {
			$char  = base62char(mt_rand(0, 61));
			$rand .= $char;
		}

		return $rand;
	}*/
?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Neil">
<title>New</title>
</head>

<body>

<div>
<?php
	echo "<tt>$username</tt>, <tt>$password</tt>, <tt>$first</tt>, <tt>$last</tt>\n";
	echo "Password is 12345? ";
	if(crypt("12345", $password) == $password) {
		echo "yes";
	} else {
		echo "no";
	}
?>
</div>

</body>

</html>

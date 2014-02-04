<?php
	/** this is not needed for versions > 5.3 */
	private function password($plain) {
		$salt = strtr(base64_encode(mcrypt_create_iv(22/*16*/, MCRYPT_DEV_URANDOM)), '+', '.');
		/* Blowfish: "$2a" + "$xx" xx=number of iterations + "$" + 22chars */
		return crypt($plain, "$2a$07$".$salt);
	}

	session_start();
	$db = new mysqli("127.0.0.1", "public", "12345", "ecse-428")
		or die("Connect failed: ".$sql->connect_error);

	$username = strip_tags(stripslashes($db->escape_string($_REQUEST["username"])));
	$password = encrypt($_REQUEST["password"]);
	/* no php 5.5 avilable
	$password = password_hash($_REQUEST["password"], PASSWORD_DEFAULT); */

	/* uhn weird */
	/*$salt = openssl_random_pseudo_bytes(22);
	$salt = '$2a$%13$' . strtr($salt, array('_' => '.', '~' => '/'));*/

	/* good enough */
	/*$salt = uniqid(mt_rand(), true);*/

	/* good */
	/*$fp = fopen('/dev/urandom', 'r');
	$random = fread($fp, 32);
	fclose($fp);*/

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



	if ($password_hash === crypt($form->password, $password_hash))
    // password is correct
	else
    // password is wrong
?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Neil">
<title>Index</title>
</head>

<body>
<p>Test</p>

<div>
<?php
	echo $_SERVER['HTTP_USER_AGENT']." ";
	$message = htmlspecialchars($_REQUEST["message"]);
	if($message) {
		echo $message."\n";
	} else {
		echo "No message.\n";
	}
?>
</div>

<form method = "get" action = "index.php">
<div>
Message: <input type = "text" name = "message" value = "Foo">
</div>
<div>
<input type = "submit" value = "Okay">
<input type = "reset" value = "Reset">
</div>
</form>

<hr>

<form method = "get" action = "login.php">
<div>
Username: <input type = "text" name = "username">
</div>
<div>
Password: <input <input type = "password" name = "password">
</div>
<div>
<input type = "submit" value = "Login">
<input type = "reset" value = "Reset">
</div>
</form>

<div>
<?php
	phpinfo();
?> 
</div>

</body>

</html>

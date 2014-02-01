<?php
	//session_start();
	//if(isset($_SESSION['username']) && isset($_SESSION['password'])) {
	//	header(“Location: content.php\n\n”);
	//}
?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Neil">
<title>Test</title>
</head>

<body>
<p>Test</p>
<form method="post" action="login.php">
<p>
Username: <input type="text" name="username">
</p>
<p>
Password: <input <input type="password" name="password">
</p>
<p>
<input type="submit" name="login" value="Login">
<input type="reset" name="reset" value="Reset">
</p>
</form>
<?php
	echo "Hello";
	session_start();
	$id = session_id();
	echo "$id";
	if(isset($_SESSION['views']))
		$_SESSION['views'] = $_SESSION['views']+ 1;
	else
		$_SESSION['views'] = 1;
	session_destroy();
?>

</body>

</html>

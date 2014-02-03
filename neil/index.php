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

<?php
	session_start();
	include "session.php";	
	
	$loggeduser = check_login();	// $loggeduser stores the logged username. Use freely.
	if ($loggeduser == "null") {	// If no one is logged in, go to loginpage.
		header("Location: loginpage.php");
		die();
	} else {
		header("Location: mainmenu.php");
	}
	$privilege = check_privilege($loggeduser);	// $privilege stores the user's privilege. Use freely.
?>

<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Neil">
<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
<link rel = "stylesheet" type = "text/css" href = "style.css">
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

<hr/>

Logon:

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

<hr/>

New:

<p>
Click <a href = "new.php">here to create a new user</a>.
</p>

<hr/>

<?php
	phpinfo();
?> 
</div>

</body>

</html>

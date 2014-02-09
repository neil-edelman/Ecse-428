<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database();
?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Team RMS">
<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
<link rel = "stylesheet" type = "text/css" href = "style.css">
<title>RMS</title>
</head>

<body>
<?php
	if(isset($_REQUEST["message"])) {
		$message = htmlspecialchars($_REQUEST["message"]);
		echo "<p>".$message."</p>\n";
	}
	echo "<p>status: ".$s->status()."</p>\n";
?>

<div>
<?php
	if($user = $s->get_user()) {
		echo "<h1>Welcome ".$user."</h1>\n";
?>
<p>
<form method = "get" action = "logoff.php">
<input type = "submit" value = "Log Off">
</form>
</p>

<?php
	} else {
?>
<h1>Log In</h1>

<form method = "get" action = "login.php">

<h2>Username</h2>
<p>Please enter the username.</p>
<p><label>Username:</label><input type = "text" name = "username"></p>
<h2>Password</h2>
<p>Please enter the password.</p>
<p><label>Password:</label><input type = "password" name = "password"></p>
<p>
<input type = "submit" value = "Login">
<input type = "reset" value = "Reset">
</p>

</form>

<?php
	}
?>

<hr/>

New:

<p>
(We probably should limit this to admins, but we have to provide a initial
 login; perhaps check if the table of users is empty?)
Click <a href = "new.php">here to create a new user</a>.
</p>

</div>

</body>

</html>

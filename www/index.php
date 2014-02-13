<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");

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
	// message
	if(isset($_REQUEST["message"])) {
		$message = htmlspecialchars($_REQUEST["message"]);
		echo "<p>".$message."</p>\n";
	}
?>

<div>
<?php
	$user = null;
	if(!($user = $s->get_user())) {
		// not logged in
		if(isset($_REQUEST["username"]) && isset($_REQUEST["password"])) {
			// logging in
			/* Neil: I don't know if these escapes are correct */
			$user = strip_tags(stripslashes($db->escape_string($_REQUEST["username"])));
			$pass = $_REQUEST["password"];
			$s->login($user, $pass) or $user = null;
		}
	} else if(isset($_REQUEST["logoff"])) {
		// logging out
		$s->logoff();
		$user = null;
	}
	if($user && ($info = $s->user_info($user))) {
		echo "<h1>Welcome ".$info["FirstName"]." ".$info["LastName"]." (".$user.")</h1>\n";
?>
<p>
<form method = "get">
<input type = "submit" name = "logoff" value = "Log Off">
</form>
</p>

<?php
	} else {
?>
<h1>Log In</h1>

<form method = "get">

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
	echo "<p>(Status: ".$s->status().")</p>\n";
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

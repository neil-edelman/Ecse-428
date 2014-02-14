<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");

	$username = null;
	$password = null;
	isset($_REQUEST["username"])  and $username = strip_tags(stripslashes($_REQUEST["username"]));
	isset($_REQUEST["password"])  and $password = $_REQUEST["password"];

	$is_complete = false;
	$username and $password and $is_complete = true;
	if($is_complete && $s->login($username, $password)) {
		header("Location: mainmenu.php");
		exit();
	}
?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
		<link rel = "stylesheet" type = "text/css" href = "style.css">
		<title>Login</title>
    </head>
    <body>
        <form method="post">
            <h1>Log on</h1>

			<h2>Username</h2>
			<p>Please enter the username <em>provided by your manager.</em></p>
			<div><label>Username:</label> <input type="text" name="username" value = "<?php echo $username?>"/></div>

			<h2>Password</h2>  			 
			<p>Please enter the password <em>provided by your manager.</em></p>
			<div><label>Password:</label> <input type="password" name="password"/></div>

			<p></p>
			<div><label></label><input type="submit" value="Login"></div>
        </form>
		<p></p>

<?php
	if($is_complete) {
		echo "<p>\nServer returned: ".$s->status().".\n</p>\n";
?>

		<h3>Forgotten password?</h3>

<p>
If you forgot your password, perform any of the following:
<ol>
<li></li>
<li></li>
<li></li>
</ol>
</p>

<?php
	}
?>

</body>
</html>

<?php

	include "session.php";

	persistent_session_start();

	$db = db_login();
	$username = strip_tags(stripslashes($db->escape_string($_REQUEST["username"])));
	$password = password_hash($_REQUEST["password"]);
	$first    = strip_tags(stripslashes($db->escape_string($_REQUEST["firstname"])));
	$last     = strip_tags(stripslashes($db->escape_string($_REQUEST["lastname"])));
	$email    = $_REQUEST["email"];

?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Neil">
<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
<link rel = "stylesheet" type = "text/css" href = "style.css">
<title>New</title>
</head>

<body>

<div>
<form method = "get" action = "new.php">
<?php
	$isReady = true;
	$len = strlen($username);
	if($len <= 0) {
		$isReady = false;
	} else if($len > $user_length) {
		echo "Username is maximum $user_length characters.<br/>\n";
		$isReady = false;
	}
?>
<label>Username:</label>
<input type = "text" name = "username" value = "<?php echo $username?>"><br/>
<label>Password:</label>
<input type = "password" name = "password"><br/>
<?php
	$len = strlen($first);
	if($len <= 0) {
		$isReady = false;
	} else if($len > $first_length) {
		echo "First name is maximum $first_length characters.<br/>\n";
		$isReady = false;
	}
?>
<label>First:</label>
<input type = "text" name = "firstname" value = "<?php echo $first?>"><br/>
<?php
	$len = strlen($last);
	if($len <= 0) {
		$isReady = false;
	} else if($len > $last_length) {
		echo "Last name is maximum $last_length characters.<br/>\n";
		$isReady = false;
	}
?>
<label>Last:</label>
<input type = "text" name = "lastname" value = "<?php echo $last?>"><br/>
<label>E-Mail:</label>
<input type = "text" name = "email" value = "<?php echo $email?>"><br/>
<label>Privilege:</label>
<select name="privilege">
<option value="wait">Wait Staff</option>
<option value="cook">Cook Staff</option>
<option value="manager">Manager</option>
<option value="admin">System Admin</option>
</select><br/>
<?php
	$isDone = false;
	/* fixme: overwrites user if already exists */
	echo "<p class = 'centre'>\n";
	if($isReady && new_user($db, $username, $password, $first, $last)) {
		echo "Created user &quot;$first $last&quot; as &quot;$username&quot;.<br/>\n";
		echo "Click to <a href = 'index.php'>return to the login</a>.\n";
	} else {
?>
<input type = "submit" value = "New">
<input type = "reset" value = "Reset">
<?php
	}
	echo "</p>\n";
	$db->close();
?>

</div>
</form>
</div>

</body>

</html>

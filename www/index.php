<?php

	include "session.php";

	persistent_session_start();

	$db = link_database();
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
<p>
<?php
	echo "Server version: ".$db->server_info."\n";
	if(isset($_REQUEST["message"])) {
		$message = htmlspecialchars($_REQUEST["message"]);
		echo $message."<br/>\n";
	} else {
		echo "No message.<br/>\n";
	}
?>
</p>

<p>Are you logged in?</p>

<div>
<?php
	if(is_logged_in($db)) {
?>
Yes.<br/>
<form method = "get" action = "logoff.php">
<input type = "submit" value = "Logoff?">
</form>

<?php
	} else {
?>
No; login:<br/>

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
</div>

<?php
	}
?>

<hr/>

New:

<p>
Click <a href = "new.php">here to create a new user</a>.
</p>

</div>

</body>

</html>

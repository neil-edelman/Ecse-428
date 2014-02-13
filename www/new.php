<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");
	$user = $s->get_user() or header_error("user error");
	$info = $s->user_info($user) or header_error("user info error");
	is_admin($info) or header_error("not authorised");

	if(isset($_REQUEST["username"]))  $username = strip_tags(stripslashes($_REQUEST["username"]));
	if(isset($_REQUEST["password"]))  $password = password_hash($_REQUEST["password"]);
	if(isset($_REQUEST["firstname"])) $first    = strip_tags(stripslashes($_REQUEST["firstname"]));
	if(isset($_REQUEST["lastname"]))  $last     = strip_tags(stripslashes($_REQUEST["lastname"]));
	...

?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Team RMS">
<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
<link rel = "stylesheet" type = "text/css" href = "style.css">
<title>Create Account</title>
</head>

<body>

<body>

<h1>Add a new user account</h1>

<div>
<form method = "get">
<label>Username:</label>
<input type = "text" name = "username" value = "<?php echo $username?>"><br/>
<label>Password:</label>
<input type = "password" name = "password"><br/>
<label>First:</label>
<input type = "text" name = "first_name" value = "<?php echo $firstname?>"><br/>
<label>Last:</label>
<input type = "text" name = "last_name" value = "<?php echo $lastname?>"><br/>
<label>Email:</label>
<input type="email" name="email"><br/>
<label>Privilege:</label>
<select name="privilege">
<option value="wait">Wait Staff</option>
<option value="cook">Cook Staff</option>
<option value="manager">Manager</option>
<option value="admin">System Admin</option>
</select><br/>
<input type = "submit" value = "New">
<input type = "reset" value = "Reset">
</form>
</div>

<p>
<?php
	$isReady = true;

	if(!isset($username) || ($len = strlen($username)) <= 0) {
		$isReady = false;
	}
	if($len > Session::USERNAME_MAX) {
		echo "Username is maximum $user_length characters.<br/>\n";
	}
		$isReady = false;
	} else if() {
		$isReady = false;
	}
	$len = strlen($first);
	if($len <= 0) {
		$isReady = false;
	} else if($len > $first_length) {
		echo "First name is maximum $first_length characters.<br/>\n";
		$isReady = false;
	}
	$len = strlen($last);
	if($len <= 0) {
		$isReady = false;
	} else if($len > $last_length) {
		echo "Last name is maximum $last_length characters.<br/>\n";
		$isReady = false;
	}
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

<p>
Go back to the <a href = "index.php">main menu</a>.
</p>

</body>

</html>

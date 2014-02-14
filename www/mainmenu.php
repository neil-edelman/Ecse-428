<?php

	include "session.php";	

	$s = new Session();

	$db = $s->link_database() or header_error("database error");

	/* must be here or else caching says "user error" w/ logoff */
	if(isset($_REQUEST["logout"]) && $s->logoff()) {
		header("Location: loginpage.php");
		exit();
	}

	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
		<link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>Main Menu</title>
    </head>
    <body>

<h1>Main Menu</h1>

<p>
<?php	
	echo "You are currently logged in as ".$info["FirstName"]." "
	     .$info["LastName"]." (".$info["username"].".)\n";
?>
</p>

<p>View <a href = "viewpersonal.php">account information</a>.</p>

<?php
	if(is_admin($info)) {
		echo "<p><a href = \"addaccount.php\">Add account</a>.</p>";
	}
?>

<p><form><input type="submit" name="logout" value="Logout"></form></p>

    </body>
</html>

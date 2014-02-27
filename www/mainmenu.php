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
	/* request check in/out */
	$is_checkedin = is_checkedin($info);
	if(isset($_REQUEST["checkout"]) && $is_checkedin) {
		if($s->checkout($info)) {
			echo "<p>You have been checked out.</p>\n\n";
		} else {
			echo "<p>There was an error and you may still be checked in; ".$s->status()."</p>\n\n";
		}
		/* refesh user info; assert true */
		$info = $s->user_info($user);
	} else if(isset($_REQUEST["checkin"]) && !$is_checkedin) {
		if($s->checkin($info)) {
			echo "<p>You have been checked in.</p>\n\n";
		} else {
			echo "<p>There was an error and you may not be checked in; ".$s->status()."</p>\n\n";
		}
		/* refesh user info; assert true */
		$info = $s->user_info($user);
	}

	if(is_checkedin($info)) {
		/* this is where all the functions that depend on check in lie */
		if(is_admin($info)) {
			echo "<p><a href = \"addaccount.php\">Add account</a>.</p>\n\n";
		}
		echo "<p><form><input type=\"submit\" name=\"checkout\" value=\"Check Out\"></form></p>\n\n";
	} else {
		/* or else show the button to check in */
		echo "<p><form><input type=\"submit\" name=\"checkin\" value=\"Check In\"></form></p>\n\n";
	}
?>

<p><form><input type="submit" name="logout" value="Logout"></form></p>

    </body>
</html>

<?php
	
	include "session.php";	
	
	$s = new Session();
	
	$db = $s->link_database() or header_error("database error");
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
		<title>View Personal Information</title>
    </head>
    <body>

	<p>Here is your personal information.</p>

<ul>
<?php
	echo "<li>Username: " . $info["username"] . "</li>\n";

	echo "<li>Password (encrypted) (It also has info on the seed; DO NOT SHOW THIS EVER -Neil): " . $info["password"] . "</li>\n";

	echo "<li>First Name: " . $info["FirstName"] . "</li>\n";

	echo "<li>Last Name: " . $info["LastName"] . "</li>\n";

	echo "<li>E-mail: " . $info["Email"] . "</li>\n";

	echo "<li>Privilege: " . $info["Privilege"] . "</li>\n";

?>
</ul>

		<p>
			Go back to the <a href = "mainmenu.php">main menu</a>.
		</p>

	</body>

</html>

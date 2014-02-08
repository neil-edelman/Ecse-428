<?php

	include "session.php";

	persistent_session_start();

	/* see if the required info was sent */
	if(!isset($_REQUEST["username"]) || !isset($_REQUEST["password"])) {
		header("Location: index.php?message=CredentialsRequired");
	}

	$db = link_database();

	/* Neil: I don't know if these escapes are correct */
	$username = strip_tags(stripslashes($db->escape_string($_REQUEST["username"])));
	$password = $_REQUEST["password"];

?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Team RMS">
<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
<link rel = "stylesheet" type = "text/css" href = "style.css">
<title>Login</title>
</head>

<body>
<div>Login</div>

<div>
<?php
	/* debug! remove */
	$query = "SELECT * FROM Users";
	$result = $db->query($db->escape_string($query));
	echo "No of hits of '$query': ".$result->num_rows."<br/>\n";
	echo "These are the users:<br/>\n";
	/* omg  */
	while($row = $result->fetch_array()) {
		echo $row["username"]."; ".$row["password"]."; ".$row["FirstName"]." ".$row["LastName"]."<br/>\n";
	}
	$result->close();

	echo "You have specified: ";
	echo session_id()."; $username; $password<br/>\n";

	if(login($db, $username, $password)) {
		echo "authorised<br/>\n";
		//header("Location: index.php?message=Denyed");
	} else {
		//header("Location: index.php?message=Authorised";
		echo "denyed<br/>\n";
	}
	$db->close();

?>
Go to <a href = "index.php">here</a>.
</div>

</body>

</html>

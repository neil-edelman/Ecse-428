<?php

	include "session.php";

	/* see if the required info was sent */
	if(!isset($_REQUEST["username"]) || !isset($_REQUEST["password"])) {
		header("Location: index.php?message=CredentialsRequired");
	}

	persistent_session_start();

	$db = link_database();

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
	if($username != "") {
		echo "$username; $password<br/>\n";
	} else {
		echo "No user.<br/>\n";
	}

	if(login($db, $username, $password)) {
		echo "Rejoyce!";
	} else {
		echo "Not authorised.";
	}

/*	$query = "SELECT * FROM users WHERE username='$username'";
	$result = $db->query($query);
	if($result->num_rows == 1) {
		$entry = $result->fetch_array();
		echo "The supplied pw: '".$password."' the pw entry ".$entry["password"]."<br/>\n";
		if(password_verify($password, $entry["password"])) {
			echo "Yes! authorised.";
		} else {
			echo "No! go home.";
		}
		echo "<br/>\n";

		echo session_id()."<br/>\n";

		$session                          = session_id();
					$_SESSION["username"] = $username;
		$ip       = $_SESSION["ip"]       = $_SERVER['REMOTE_ADDR'];
		$activity = $_SESSION["activity"] = gmdate("Y-m-d H:i:s");

		$stmt = $db->prepare("INSERT INTO "
							 ."session(session_id, username, ip, activity)"
							 ." VALUES (?, ?, ?, ?)");
		if(!$stmt) die($db->error);
		$ok   = $stmt->bind_param("ssss",
								  $db->escape_string($session),
								  $db->escape_string($username),
								  $db->escape_string($ip),
								  $db->escape_string($activity));
		if($ok && $stmt->execute()) {
			echo "Success<br/>\n";
		} else {
			echo "Error: ".$db->error;
		}
		//header('Location: content.php');
	} else {
		echo "Failed<br/>\n";
		//header("location:index.php?message=invalid");
	}
	$result->close();*/

	$db->close();
?>
</div>

</body>

</html>

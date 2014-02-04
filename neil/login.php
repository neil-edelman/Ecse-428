<?php
	
	include "session.php";
	
	//local session creation
	session_start();

	//database login function from session.php
	$db = db_login();
	$username = strip_tags(stripslashes($db->escape_string($_REQUEST["username"])));
	$password = $_REQUEST["password"];
?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Neil">
<title>Login</title>
</head>

<body>
<div>Login!</div>

<div>
<?php
	$query = "SELECT * FROM users";
	$result = $db->query($query);
	echo "No of hits of '$query': ".$result->num_rows."<br/>\n";
	echo "These are the users:<br/>\n";
	while($row = $result->fetch_array()) {
		echo $row["username"]."; ".$row["password"]."; ".$row["first_name"]." ".$row["last_name"]."<br/>\n";
	}
	$result->close();

	echo "You have specified: ";
	if($username != "") {
		echo "$username; $password<br/>\n";
	} else {
		echo "No user.<br/>\n";
	}
	
	$query = "SELECT * FROM users WHERE username='$username'";
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
	$result->close();
	
	$db->close();
	?>
</div>

</body>

</html>

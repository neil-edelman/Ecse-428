<?php
	session_start();
	$db = new mysqli("127.0.0.1", "public", "12345", "ecse-428")
	or die("Connect failed: ".$sql->connect_error);
	$username = strip_tags(stripslashes($db->escape_string($_REQUEST["username"])));
	// only 5.3, need 5.5
	//$password = password_hash($_REQUEST["password"], PASSWORD_DEFAULT);
	// fixme! no: salt first!
	$password = crypt($_REQUEST["password"]);
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
	printf("Host information: %s;<br/>\n", $sql->host_info);
	
	echo "You have specified:<br/>\n";
	if($username != "") {
		echo "$username; $password<br/>\n";
	} else {
		echo "No user.<br/>\n";
	}
	echo "<br/>\n";
	
	$query = "SELECT * FROM users";
	$result = $db->query($query);
	echo "No of hits of '$query': ".$result->num_rows."<br/>\n";
	echo "These are the users:<br/>\n";
	while($row = $result->fetch_array()) {
		echo $row["username"]."; ".$row["password"]."; ".$row["first_name"]." ".$row["last_name"]."<br/>\n";
	}
	$result->close();
	
	$query = "SELECT * FROM users WHERE username='$username'";// and password='$password'";
	$result = $db->query($query);
	$entries = $result->num_rows;
	echo "No of hits of '$query': ".$entries."<br/>\n";
	$result->close();
	
	if($entries == 1) {
		echo "Congraz you may be logged in.<br/>\n";
		echo session_id()."<br/>\n";
		
		$_SESSION["username"] = $username;
		$_SESSION["ip"]       = $_SERVER['REMOTE_ADDR'];
		$_SESSION["activity"] = gmdate("Y-m-d H:i:s");
		
		$stmt = $db->prepare("INSERT INTO "
							 ."session(session_id, username, ip, activity)"
							 ." VALUES ('?', 'admin', '127.0.0.1', '2001-01-01')");
		//$stmt = $db->prepare("INSERT INTO"
		//					 ."session(session_id, username, ip, activity)"
		//					 ." VALUES (?, ?, ?, ?, ?)");
		$ok   = $stmt->bind_param("s", $db->quote("sdfg"));
		//$ok   = $stmt->bind_param("sssss",
		//	session_id(),
		//	"d",//$db->quote($_SESSION["username"]),
		//	"s",//$db->quote($_SESSION["ip"]),
		//						  "2001-01-01T12:00:00");//$db->quote($_SESSION["activity"]));
		if($ok && $stmt->execute())
			echo "Success<br/>\n";
		else
			die('Error: '.$db->error);
		//header('Location: content.php');
		//$userdata = $bd->query($sql);
	} else {
		//header("location:index.php?message=invalid");
		echo "Failed<br/>\n";
	}
	
	$db->close();
	?>
</div>

</body>

</html>

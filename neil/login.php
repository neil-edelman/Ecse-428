<?php
	session_start();
	$sql = new mysqli("127.0.0.1", "public", "12345", "ecse-428")
		or die("Connect failed: ".$sql->connect_error);
	$username = strip_tags(stripslashes($sql->escape_string($_REQUEST["username"])));
	// only 5.3, need 5.5
	//$password = password_hash($_REQUEST["password"], PASSWORD_DEFAULT);
	// no: salt first!
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
	$result = $sql->query($query);
	echo "No of hits of '$query': ".$result->num_rows."<br/>\n";
	echo "These are the users:<br/>\n";
	while($row = $result->fetch_array()) {
		echo $row["username"]."; ".$row["password"]."; ".$row["first_name"]." ".$row["last_name"]."<br/>\n";
	}
	$result->close();

	$query = "SELECT * FROM users WHERE username='$username'";// and password='$password'";
	$result = $sql->query($query);
	$entries = $result->num_rows;
	echo "No of hits of '$query': ".$entries."<br/>\n";
	$result->close();

	$sql->close();

	if($entries == 1) {
	//	session_register("username");
	//		header("location:content.php");
		echo "Success<br/>\n";
	} else {
	//		header("location:index.php?message=invalid");
		echo "Failed<br/>\n";
	}
?>
</div>

</body>

</html>

<?php
	session_start();
?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8"/>
<meta name = "Author" content = "Neil"/>
<link rel = "stylesheet" type = "text/css" href = "styles.css"/>
<title>Content</title>
</head>

</head>
<body>
<div>
Welcome <?php echo $_SESSION["username"]; ?>. Click here to <a href = "logout.php">logout</a>.
<?php
	$sql = "SELECT * FROM Users";
	$rs = mysql_query($sql) or echo "SQL Query failed.";
	echo $rs;
?>
</div>

</body>
</html>

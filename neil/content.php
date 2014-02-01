<?php
	session_start();
	if(!isset($_SESSION['username']) || !isset($_SESSION['password'])){
		header("Location: niceform.php");
	}
?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Neil">
<title>Content</title>
</head>

<body>
<p>
<a href = "logoff.php">logoff</a>
</p>

</body>

</html>

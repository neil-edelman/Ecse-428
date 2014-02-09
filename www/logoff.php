<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database();
?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Team RMS">
<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
<link rel = "stylesheet" type = "text/css" href = "style.css">
<title>Logoff</title>
</head>

<body>
<div>Logoff</div>

<div>
<?php
	if($s->logoff()) {
		echo "You have been logged off.<br/>\n";
	} else {
		echo "You have not been logged off: ".$s->status()."<br/>\n";
	}
?>
</div>

<p>
Go to <a href = "index.php">the logon page</a>.
</p>

</body>

</html>

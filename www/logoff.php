<?php
	
	include "session.php";
	
	//local session creation
	persistent_session_start();

	//database login function from session.php
	$db = link_database();
?>
<!doctype html>

<html>
<head>
<meta charset = "UTF-8">
<meta name = "Author" content = "Neil">
<title>Logoff</title>
</head>

<body>
<div>Logoff!</div>

<div>
<?php
	if(logoff($db)) {
		echo "Rejoyce!";
	} else {
		echo "Not authorised.";
	}
	$db->close();
?>
</div>

</body>

</html>

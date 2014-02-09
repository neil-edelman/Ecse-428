<?php
	session_start();
	include "session.php";	
	
	$loggeduser = check_login();	// $loggeduser stores the logged username. Use freely.
	if ($loggeduser == "null") {	// If no one is logged in, return to loginpage.
		header("Location: loginpage.php");
		die();
	}
	$privilege = check_privilege($loggeduser);	// $privilege stores the user's privilege. Use freely.
?>

<!DOCTYPE html>
<!--
Login Page
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>View Personal Information</title>
    </head>
    <body>

	<p>Here is your personal information.</p>

	<?php

		// Query from the database's Users table all the values we need.

		$server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");
		$user = mysqli_fetch_row(mysqli_query($server, "SELECT * FROM Users WHERE Username = '$loggeduser';"));
	
		$password = $user[1];
		$firstname = $user[2];
		$lastname = $user[3];
		$email = $user[4];
		

		// Print all the values we queried.

		echo "<br>Username: " . $loggeduser;

		echo "<br>Password (encrypted): " . $password;

		echo "<br>First Name: " . $firstname;

		echo "<br>Last Name: " . $lastname;

		echo "<br>E-mail: " . $email;

		echo "<br>Privilege: " . $privilege;

	?>   	  
		        
    </body>
</html>

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

		// First, find a cookie (client-sided) and check if it's ID in the Session ID table (server-sided).

		$mycookie = $_COOKIE['superCookie'];
		setcookie("superCookie", "", time()-3600);
		$server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");
		$cookie_query = "SELECT * FROM SessionID WHERE ID = '$mycookie';";   
		$query_result = mysqli_query($server, $cookie_query);
		if (mysqli_num_rows($query_result) == 1) {
			$thecookie = mysqli_fetch_row($query_result);
		}
		if (mysqli_num_rows($query_result) == 0) {
			header("Location: http://www.payom.ca/rms/tiberiu/expired.php");
		}
		if (mysqli_num_rows($query_result) > 1) {
			echo "<br> WARNING: More than one result in database query! <br>";
		}
	

		// Second, query from the database's Session ID table all the values we need.

		$username = $thecookie[1];
		$privilege = $thecookie[2];
		
		$user = mysqli_fetch_row(mysqli_query($server, "SELECT * FROM Users WHERE Username = '$username';"));

		$session_id = htmlspecialchars($_COOKIE["superCookie"]);		
		$password = $user[1];
		$firstname = $user[2];
		$lastname = $user[3];
		$email = $user[4];
		

		// Third, print all the values we queried.
		
		echo "<br>Session ID (DEBUG) : " . $session_id;		

		echo "<br>Username: " . $username;

		echo "<br>Password: " . $password;

		echo "<br>First Name: " . $firstname;

		echo "<br>Last Name: " . $lastname;

		echo "<br>E-mail: " . $email;

		echo "<br>Privilege: " . $privilege;

	?>   	  
		        
    </body>
</html>

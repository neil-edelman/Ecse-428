<!DOCTYPE html>
<!--
Login Page
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>Logged out!</title>
    </head>
    <body>

	<p>You have been inactive for too long and have subsequently been logged out.</p>
	<p>Please log back in.</p>

	<form method="post"> 
		<h1>Main Menu</h1><br>
		<input type="submit" value="Log In">
        </form>
	<?php
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			header("Location: http://www.payom.ca/rms/jonathan/loginpage.php");
			die();
		}
	?>
		        
    </body>
</html>

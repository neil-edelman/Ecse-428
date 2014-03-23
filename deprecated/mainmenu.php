<!DOCTYPE html>
<!--
Login Page
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>Main Menu</title>
    </head>

    <body>
        <form method="post">
            <h1>Main Menu</h1><br>

            <input type="submit" value="Logout">
        </form>

        <?php
			$mycookie = $_COOKIE['superCookie'];

            if ($_SERVER["REQUEST_METHOD"] == "POST"){
				setcookie("superCookie", "", time()-3600);
				// Create connection
				$server= mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms2");

				// Check connection
				if (mysqli_connect_errno()) {
					echo "Failed to connect to MySQL: " . mysqli_connect_error();
				}

				$sqlQuery = "SELECT * FROM SessionID WHERE ID = '$mycookie';";
				$result = mysqli_query($server, $sqlQuery);

				if (mysqli_num_rows($result) == 1) {
					$sqlSession = "DELETE FROM payomca_rms2 . SessionID WHERE SessionID . ID = '$mycookie';";
					mysqli_query($server, $sqlSession);

					header("Location: loginpage.php");
					die();
				}


            }
        ?>

	<br>

	<a href="http://www.payom.ca/rms/tiberiu/viewpersonal.php">View Personal Information</a>



        <br><br>

    </body>
</html>

<?php
	session_start();
	include "session.php";	
?>

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
		   
		    <input type="submit" name="viewaccount" value="View Account Information"><br>
		    <input type="submit" name="addaccount" value="Add Account"><br>
            <input type="submit" name="logout" value="Logout"><br>
            
        </form>
        
        <?php
			
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
				if (isset($_POST['logout'])) {
					//DELETE FROM `payomca_rms`.`SessionID` WHERE `SessionID`.`session_id` = 'krcngtko6d53i4u8g9irokcl75'
					$session_id = session_id();
					$server= mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");
					if (mysqli_connect_errno()) {
						echo "Failed to connect to MySQL: " . mysqli_connect_error();
					}
				
					echo "Session ID: " . $session_id;
				
					$sqlQuery = "SELECT * FROM SessionID WHERE SessionID . session_id = '$session_id';";                        
					$result = mysqli_query($server, $sqlQuery);

					if (mysqli_num_rows($result) == 1) {
						$sqlSession = "DELETE FROM payomca_rms . SessionID WHERE SessionID . session_id = '$session_id'";
						mysqli_query($server, $sqlSession);

						echo "YES";
						header("Location: loginpage.php");
						die();
					}
				}
				
				if (isset($_POST['addaccount'])) {
					header("Location: addaccount.php");
					die();
				}
				
				if (isset($_POST['viewaccount'])) {
					
				}
			}
        ?>
        
        <br><br>     	  
		        
    </body>
</html>

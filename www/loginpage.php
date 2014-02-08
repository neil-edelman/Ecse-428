<?php
	include "session.php";	
	persistent_session_start();
	$loggeduser = check_login();	// $loggeduser stores the logged username. Use freely.
	if ($loggeduser != "null") {	// If someone is logged in, go to mainmenu.
		header("Location: mainmenu.php");
	}	
?>

<!DOCTYPE html>
<!--
Login Page
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <form method="post">
            <h1>LOGIN</h1><br>    

			<h2>Username</h2>
            <p>Please enter the username <em>provided by your manager.</em></p>
            Username: <input type="text" name="username"><br>
            
            <h2>Password</h2>  			 
			<p>Please enter the password <em>provided by your manager.</em></p>
            Password: <input type="password" name="password"><br><br>          
           
            <input type="submit" value="Login">
        </form>
        
        <?php
			echo "heeloo";
            if ($_SERVER["REQUEST_METHOD"] == "POST"){             			
				
                $db = link_database();
              
                $username = strip_tags(stripslashes($db->escape_string($_POST['username'])));                 
                $password = $_POST['password'];            

                if (!(empty($username) || empty($password))) {			
					
					//Send query to data base to search for the username and password inputted	
					$sqlQuery = "SELECT * FROM Users WHERE Username = '$username'";                        
                    $result = mysqli_query($db, $sqlQuery);                                              
                                            
                    //If the query return one result, then give access and add user session id.
                    if (mysqli_num_rows($result) == 1) {	
						$entry = $result->fetch_array();
												
						if(password_verify($password, $entry["password"])) {						
							
							echo "Both password and username passed!";
							
							$session_id = session_id();
							$_SESSION["username"] = $username;
							$ip = $_SESSION["ip"] = $_SERVER['REMOTE_ADDR'];
							//$activity = $_SESSION["activity"] = gmdate("Y-m-d H:i:s"/* lol no "M d Y H:i:s"*/);						
				
							$sqlSession = $db->prepare("INSERT INTO payomca_rms.SessionID (session_id, username, ip, activity) VALUES (?, ?, ?, now())");
							
							if (!$sqlSession) {
								die($db->error);
							}
							
							$ok = $sqlSession->bind_param("sss", $db->escape_string($session_id), $db->escape_string($username), $db->escape_string($ip));
							
							if ($ok && $sqlSession->execute()) {													
								header("Location: mainmenu.php");
								$result->close();
								$db->close;								
								die();
							} else {
								echo "Error :", $db->error;
							}			
						} else {
							echo "Error: Invalid Password";
						}					
					} else {
						echo "Error: Invalid Username or Password";
					}			                      
                } else {
                        echo "Error: Invalid data entered on form";
                }
                $result->close();
				$db->close;	
            }           
        ?>
        
        <br><br>     	  
   
		<h3>Forgotten password?</h3>
		If you forgot your password, perform any of the following:	<br><br>	
		
		1) <br>
		2) <br>
		3) <br>
		        
    </body>
</html>

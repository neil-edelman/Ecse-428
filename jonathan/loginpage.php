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
            if ($_SERVER["REQUEST_METHOD"] == "POST"){
                $username = $_POST['username'];
                $password = $_POST['password'];

                if (!(empty($username) || empty($password))) {

					// Create connection
                    $server= mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");

                    // Check connection
                    if (mysqli_connect_errno()) {
                        echo "Failed to connect to MySQL: " . mysqli_connect_error();
                    }						
					
					//Send query to data base to search for the username and password inputted	
					$sqlQuery = "SELECT * FROM Users WHERE Username = '$username' AND Password = '$password';";                        
                    $result = mysqli_query($server, $sqlQuery);                                              
                                              
                    //If the query return one result, then give access and add user session id.
                    if (mysqli_num_rows($result) == 1) {						
						//Retieve privilege value and create unique id
						$row = mysqli_fetch_row($result);						
						$ses_id = uniqid();
						
						//Create cookie. The 4th parameter makes the cookie usable in the entire domain.
						setcookie("superCookie", $ses_id, time()+3600, '/');						
											
						$sqlSession = "INSERT INTO payomca_rms.SessionID (ID, Username, Privilege) VALUES ('$ses_id', '$username', '$row[5]');";
						mysqli_query($server, $sqlSession); 
						
						header("Location: mainmenu.php");
                        die();
					} else {
						echo "Error: Invalid Username or Password";
					}                       
                } else {
                        echo "Error: Invalid data entered on form";
                }
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

<!DOCTYPE html>
<!--
Login Page
-->

<html>
    <head>
        <meta charset="UTF-8">
        <title>Temp Page</title>
    </head>
    <body>
        <form method="post">
            <h1>Temp Page</h1><br>    

			<p>League of Legends is better than DOTA!!</p>
			<p>The moon is made of Cheese!!!</p>
			
            <input type="submit" value="Back">
        </form>
        
        <?php
		    if ($_SERVER["REQUEST_METHOD"] == "POST"){
				header("Location: mainmenu.php");
				die();
			}
        ?>
        
        <br><br>     	  
		        
    </body>
</html>

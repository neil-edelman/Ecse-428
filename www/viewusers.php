<?php
    include "session.php";
    include "modifyusers.php";

    $s = new Session();
    $g = new modifyusers();

    $db = $s->link_database() or header_error("database error");
    $user = $s->get_user() or header_error("user timeout error");

    $all_users = $g->get_all_users($db);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
                <link rel = "stylesheet" type = "text/css" href = "style.css">
		<title>Edit Users</title>
    </head>
    <body>
        <h1>Edit Users</h1>               
        
		<form name="User List" method="post" action="edituser.php">
            Select the user to be edited with his/her username:<br>
			<select name="username" onchange="this.form.submit()">
			<option value=""></option>
			
        <?php    			
			while($row = $all_users->fetch_array(MYSQLI_NUM)){
				echo "<option value=\"$row[0] $row[2] $row[3] $row[4] $row[5]\">".$row[0]." (".$row[2]." ".$row[3].")</option>";
			}
        ?>   
            </select>		
        </form>
		
        <p>
			<br>
			Go back to the <a href = "mainmenu.php">main menu</a>.			
        </p>
        
    </body>
</html>

     

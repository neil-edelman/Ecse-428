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
		<title>View Users</title>
    </head>
    <body>
        <h1>View Users</h1>        
           
        <form name="select" method="post" action="viewtables.php">
            Sort the table by:
            <select name="select_ordering" onchange="this.form.submit()">
                <option value="empty">CHOOSE OPTION</option>
                <option value="tablenumber ASC">Table Number (INCR)</option>
                <option value="tablenumber DESC">Table Number (DECR)</option>
                <option value="maxsize ASC">Max Size (INCR)</option>
                <option value="maxsize DESC">Max Size (DECR)</option>
                <option value="currentsize ASC">Current Size (INCR)</option>
                <option value="currentsize DESC">Current Size (DECR)</option>
                <option value="status ASC">Status (INCR)</option>
                <option value="status DESC">Status (DECR)</option>
            </select>
        </form>          
        
		<form name="User List" method="post" action="edituser.php">
            Sort the table by:
			<select name="username" onchange="this.form.submit()">
        <?php    
			//echo "start<br>";
            
			
			/*while($row = $all_users->fetch_array(MYSQLI_NUM)){
				echo "$row[0]<br>";
			}
			$temp = mysqli_num_rows($all_users);
			echo "Number of users: $temp<br>";
			
			echo "end<br>"; */
			
			while($row = $all_users->fetch_array(MYSQLI_NUM)){
				echo "<option value='$row[0]'>".$row[0]."</option>";
			}
        ?>   
            </select>		
        </form>
		
        <p>
			<br>
            Go to <a href = "createtable.php">Create Tables</a><br>
			Go back to the <a href = "mainmenu.php">main menu</a>.			
        </p>
        
    </body>
</html>

     

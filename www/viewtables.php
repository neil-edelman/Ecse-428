<?php
    include "session.php";
    include "modifytables.php";

    $s = new Session();
    $g = new modifytables();

    $db = $s->link_database() or header_error("database error");
    $user = $s->get_user() or header_error("user timeout error");

    $all_tables = $g->get_all_tables($db);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
<<<<<<< HEAD
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
                <link rel = "stylesheet" type = "text/css" href = "style.css">
		<title>View Tables</title>
    </head>
    <body>
        <h1>View Tables</h1>        
           
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
        
        <?php            
            if ($ordering = filter_input(INPUT_POST, 'select_ordering', FILTER_DEFAULT)) {                
                $all_tables = $g->get_all_tables($db, $ordering); 
            } else {                
                $all_tables = $g->get_all_tables($db, "default");
           }
            
            echo $g->display_all_tables($all_tables, $db);      
                
            $all_tables->close();
        ?>                    
        
        <p>
            <br>Go back to the <a href = "mainmenu.php">main menu</a>.
        </p>
        
    </body>
</html>

        <meta name = "Author" content = "Team RMS">
        <link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>View Tables</title>
    </head>
    <body>
        <h1>View Tables</h1>

        <?php
             echo $g->display_all_tables($all_tables);

             $all_tables->close();
        ?>

        <p>
            <br>Go back to the <a href = "mainmenu.php">main menu</a>.
        </p>

    </body>
</html>


<?php
include "session.php";
include "modifyitems.php";

$s = new Session();
$g = new modifyitems();

$db = $s->link_database() or header_error("database error");
$user = $s->get_user() or header_error("user timeout error");

$all_items = $g->get_all_items($db);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name = "Author" content = "Team RMS">
        <link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>View Menu Items</title>
    </head>
    <body>
        <h1>View Menu Items</h1>        

        <form name="select" method="post" action="viewitems.php">
            Sort the items by:
            <select name="select_ordering" onchange="this.form.submit()">
                <option value="empty">CHOOSE OPTION</option>
                <option value="`Item ID` ASC">Item ID (INCR)</option>
                <option value="`Item ID` DESC">Item ID (DECR)</option>
                <option value="`Name` ASC">Name (INCR)</option>
                <option value="`Name` DESC">Name (DECR)</option>
                <option value="`Cost` ASC">Price (INCR)</option>
                <option value="`Cost` DESC">Price (DECR)</option>
            </select>
        </form>          

        <?php
        if ($ordering = filter_input(INPUT_POST, 'select_ordering', FILTER_DEFAULT)) {
            $all_items = $g->get_all_items($db, $ordering);
        } else {
            $all_items = $g->get_all_items($db, "default");
        }

        echo $g->display_all_items($all_items, $db);

        $all_items->close();
        ?>                    

        <p>
            <br>Go to <a href = "createitem.php">Create Menu Item</a><br>
            Go back to the <a href = "mainmenu.php">main menu</a>.			
        </p>

    </body>
</html>
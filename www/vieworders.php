<?php
    include "session.php";
    include "modifyorder.php";

    $s = new Session();
    $g = new modifyorder();

    $db = $s->link_database() or header_error("database error");
    $user = $s->get_user() or header_error("user timeout error");

    $all_orders = $g->get_table_orders($db);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name = "Author" content = "Team RMS">
        <link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>View Orders</title>
    </head>
    <body>
        <h1>View Orders</h1>

        <?php
             echo $g->display_all_orders($all_orders);

             $all_orders->close();
        ?>

        <p>
            <br>Go back to the <a href = "mainmenu.php">main menu</a>.
        </p>

    </body>
</html>

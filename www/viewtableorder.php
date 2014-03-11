<?php

    include "session.php";
    include "modifydetails.php";

    $s = new Session();
    $g = new modifydetails();

    $db = $s->link_database() or header_error("database error");
    $details = $g->get_order_details($db);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name = "Author" content = "Team RMS">
        <link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>Orders Details</title>
    </head>
    <body>
        <h1>View Orders Details</h1>

        <?php
             $row[0] = $_REQUEST['tablenumber'];
             echo $g->display_order_details($details, $row[0]);

             $details->close();
        ?>

        <p>
            <br>To add to this order <a href = "addtoorder.php">CLICK HERE!</a>.
            <p></p>
            <br>Go back to the <a href = "mainmenu.php">main menu</a>.
        </p>

    </body>
</html>

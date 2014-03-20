<?php
include "session.php";
include "revenues.php";

$s = new Session();
$r = new Revenues();

$db = $s->link_database() or header_error("database error");
$user = $s->get_user() or header_error("user timeout error");
$info = $s->user_info($user) or header_error("user info error");
is_admin($info) or header_error("not authorised");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name = "Author" content = "Team RMS">
        <link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>View Revenues</title>
    </head>
    <body>
        <h1>View Revenues</h1>        

        <?php
        echo $r->get_daily_revenues($db);
        ?>                    

        <p>
            <br>
            Go back to the <a href = "mainmenu.php">main menu</a>.			
        </p>

    </body>
</html>



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

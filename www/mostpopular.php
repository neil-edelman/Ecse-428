<?php
include "session.php";
include "modifyitems.php";

$s = new Session();
$g = new modifyitems();

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
        <title>View Popular Menu Items</title>
    </head>
    <body>
        <h1>View Popular Menu Items</h1>        

        <form name="select" method="post" action="mostpopular.php">
            Sort the items by:
            <select name="select_ordering" onchange="this.form.submit()">
                <option value="empty">CHOOSE OPTION</option>
                <option value="`popularity` ASC">Popularity (INCR)</option>
                <option value="`popularity` DESC">Popularity (DECR)</option>
                <option value="`popularity` * `Cost` ASC">Revenue (INCR)</option>
                <option value="`popularity` * `Cost` DESC">Revenue (DECR)</option>
            </select>
        </form>          

        <?php
        if ($ordering = filter_input(INPUT_POST, 'select_ordering', FILTER_DEFAULT)) {
            $items = $g->get_popular_items($db, $ordering);
        } else {
            $items = $g->get_popular_items($db, "`popularity` DESC");
        }

        echo $g->display_popular_items($items, $db);

        $items->close();
        ?>                    

        <p>
            <br>Go back to the <a href = "mainmenu.php">main menu</a>.			
        </p>

    </body>
</html>
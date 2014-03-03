<?php

    include "session.php";

    $s = new Session();

    $db = $s->link_database() or header_error("database error");
    $user = $s->get_user() or header_error("user timeout error");
    $info = $s->user_info($user) or header_error("user info error");

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name = "Author" content = "Team RMS">
        <link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
        <link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>Create Order</title>
    </head>
    <body>

        <form method="post">
            <h1><font color="7700FF">View Order</font></h1>

            <h2>View order of which Table?</h2>
            <p>Please enter the table associated with this order.</p>
            <div><label>Table of Order:</label> <input type="text" name="tableid"/></div>

            <p></p>
            <div><label></label><input type="submit" value="View order"></div>

        </form>
        <p></p>

        <p>Cancel and return to the <a href = "mainmenu.php">main menu</a>.</p>

<?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            echo $tableid;

            if(!empty($tableid))
            {
                $server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");

            if (mysqli_connect_errno()) {
                echo "Failed to connect to MySQL during orderid generation: " . mysqli_connect_error();
            }

            $result = "SELECT * FROM `payomca_rms`.`Order`";
            $stmt = mysqli_prepare ($server, $sql);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            echo $result;

//            $tableid = null;
//            $orderid = null;
            }
            else
            {
                echo "Error: No Table ID given.";
            }
        }

//          isset($_REQUEST["tableid"]) and $tableid = $_REQUEST["tableid"];    // When the tableid field is set, store in in $tableid.
//          isset($_REQUEST["orderid"]) and $orderid = $_REQUEST["orderid"];
//        if (!empty($tableid)) {
//
//            $server= mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");
//            if (mysqli_connect_errno()) {
//                echo "Failed to connect to MySQL: " . mysqli_connect_error();
//            }
//
//            $sql = "SELECT * FROM `payomca_rms`.`OrderContain`";
//            $stmt = mysqli_prepare ($server, $sql);
//            mysqli_stmt_execute($stmt);
//            mysqli_stmt_store_result($stmt);
//
//            $itemid = null;
//            $quantity = null;
//            $comment = null;

//            isset($_REQUEST["itemid"]) and $itemid = $_REQUEST["itemid"];
//            isset($_REQUEST["quantity"]) and $quantity = $_REQUEST["quantity"];
//            isset($_REQUEST["comment"]) and $comment = $_REQUEST["comment"];

//            echo $itemid;
//            echo $quantity;
//            echo $comment;

//            <p>Back to <a href = "mainmenu.php">Main Menu</a>.</p>

//        } else {
//            echo "Error: No Table ID given.";
//        }

//    ?>
        <p></p>

</body>
</html>

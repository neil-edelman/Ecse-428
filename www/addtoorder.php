<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");

	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	
	$containid = uniqid();
	$orderid = $_SESSION['orderid'];	// Getting $orderid
	$itemid = null;
	$quantity = null;
	$comment = null;
	
	isset($_REQUEST["itemid"]) and $itemid = $_REQUEST["itemid"];		// When the itemid field is set, store in in $itemid.
	isset($_REQUEST["quantity"]) and $quantity = $_REQUEST["quantity"];
	isset($_REQUEST["comment"]) and $comment = $_REQUEST["comment"];

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
		<link rel = "stylesheet" type = "text/css" href = "style.css">
		<title>Add To Order</title>
    </head>
    <body>
        <form method="post">
            <h1><font color="990000">Add to Order</font></h1>

			<h2>Item</h2>
			<p>Please enter the ordered item's ID.</p>
			<div><label>Item ID:</label> <input type="text" name="itemid"/></div>

			<h2>Quantity</h2>  			 
			<p>Please enter the requested quantity of the item.</p>
			<div><label>Quantity:</label> <input type="text" name="quantity"/></div>

			<h2>Comment</h2>  			 
			<p>Please enter any special requests by the client. (300 characters max)</p>
			<div><label>Comment:</label> <input type="text" name="comment"/></div>

			<p></p>
			<div><label></label><input type="submit" value="Add to order"></div>			
        </form>
		<p></p>
		<p>Return to the <a href = "mainmenu.php">main menu</a>.</p>

<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (empty($itemid) || empty($quantity)) {
			echo "Error: One or more required field unfilled.";
		} else {

			$server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms2");
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}

			$sql = "INSERT INTO payomca_rms2 . OrderContain (containid, orderid, itemid, quantity, comment) VALUES ('$containid', '$orderid', '$itemid', '$quantity', '$comment');";
			$result = mysqli_query($server, $sql);
			if ($result >= 1 ) {
				echo "Item successfully added to the order!";
			}
		}
	}

?>


</body>
</html>

<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");

	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");

	$a = mysqli_query($db, "SELECT MAX(orderid) FROM `payomca_rms2`.`Order`");	// Getting $orderid
	$b = mysqli_fetch_row($a);
	$orderid = $b[0];
	
	$item = null;
	$quantity = null;
	$comment = null;
	
	isset($_REQUEST["item"]) and $item = $_REQUEST["item"];		// When the item field is set, store in in $item.
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
			<div><label>Item:</label>
			<select name="item">
                <?php				
				$sqlgetorder = "SELECT * FROM `payomca_rms2`.`MenuItems`";	
				$res = mysqli_query($db, $sqlgetorder);
				while($row = $res->fetch_array(MYSQLI_NUM)){
					echo "<option value=\"$row[0]\">$row[1]</option>";
				}
				?>
            </select></div>
					
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
		if (empty($item) || empty($quantity)) {
			echo "Error: One or more required fields unfilled.";
		} else {

			$sqlinsert = "INSERT INTO payomca_rms2 . OrderContain (orderid, itemid, quantity, comment) VALUES ('$orderid', '$item', '$quantity', '$comment');";
			$result = mysqli_query($db, $sqlinsert);
			if ($result >= 1 ) {
				echo "Item successfully added to the order!";
			}
		}
	}

?>


</body>
</html>

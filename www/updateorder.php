<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");

	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	
	$itemnumber = $_SESSION['itemnumber'];
	
	$orderid = $_SESSION['idorder'];	// Getting $orderid
	
	$server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms2");
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
	
	// THEORDER = the order to update.
	$theorder = mysqli_fetch_row(mysqli_query($server, "SELECT * FROM `payomca_rms2`.`Order` WHERE `orderid` = $orderid"));	// Only 1 result will exist.
	
	$sqlitem = "SELECT * FROM `payomca_rms2`.`OrderContain` WHERE `orderid` = $orderid";			
	$stmt = mysqli_prepare ($server, $sqlitem);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $rows = mysqli_stmt_num_rows($stmt);	// Count the # of items for this order.
	
	// ALLCONTAINS = all items contained in the current order.
	$allcontains = mysqli_query($server, "SELECT * FROM `payomca_rms2`.`OrderContain` WHERE `orderid` = $orderid");
	

	for ($i = 1; $i <= $itemnumber; $i++) {
		// THECONTAINS = the current item contained in the order, to display/update.
		$thecontains = mysqli_fetch_row($allcontains);
	}
	
	$containid = $thecontains[0];
	$situation = $theorder[2];	// Reminder: "status" is a reserved word in the database. Using "situation" instead.
	$tableid = $theorder[1];
	$itemid = $thecontains[2];
	$quantity = $thecontains[3];
	$comment = $thecontains[4];
	
	// Update values as the HTML fields are set by the user.
	isset($_REQUEST["situation"]) and $situation = $_REQUEST["situation"];
	isset($_REQUEST["tableid"]) and $tableid = $_REQUEST["tableid"];
	isset($_REQUEST["itemid"]) and $itemid = $_REQUEST["itemid"];
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
		<title>Update Order</title>
    </head>
    <body>
        <form method="post">
            <h1><font color="009999">Update Order</font></h1>

			<h2>Status</h2>
			<p>Change status of the Order.</p>
			<div><label>Order ID:</label> <input type="text" name="situation" value="<?php echo $situation; ?>"></div>
			
			<h2>Table</h2>
			<p>Change the table associated with this order.</p>
			<div><label>Table:</label> <input type="text" name="tableid" value=<?php echo $tableid; ?>></div>
			
			<h2>Item</h2>
			<p>Change the ordered item's ID.</p>
			<div><label>Item ID:</label> <input type="text" name="itemid" value=<?php echo $itemid; ?>></div>

			<h2>Quantity</h2>  			 
			<p>Change the requested quantity of the item.</p>
			<div><label>Quantity:</label> <input type="text" name="quantity" value=<?php echo $quantity; ?>></div>

			<h2>Comment</h2>  			 
			<p>Change the special requests by the client. (300 characters max)</p>
			<div><label>Comment:</label> <input type="text" name="comment" value="<?php echo $comment; ?>"></div>

			<p></p>
			<div><label></label><input type="submit" name="next" value="View Next Item in Order"></div>

			<p></p>
			<div><label></label><input type="submit" name="updating" value="Update Order"></div>			
			
        </form>
		<p></p>
		<p>Return to the <a href = "mainmenu.php">main menu</a>.</p>

<?php

	
	if(isset($_REQUEST["next"])) {

		if ($itemnumber == $rows) {
			$_SESSION['itemnumber'] = 1;	// Reached the final item. Rollback to 1st.
		} else {
			$_SESSION['itemnumber'] = $itemnumber + 1;	// Show next item.
		}
		header("Location: updateorder.php");
	}
	
	if(isset($_REQUEST["updating"])) {

		if (empty($situation) || empty($tableid) || empty($itemid) || empty($quantity)) {
			echo "Error: One or more required fields are empty. Only Comment can be empty.";
		} else if (strlen($comment) >= 300) {
			echo "Error: Comment is too large. Please limits comments to 300 characters.";
		}
		else {
			
			$server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms2");
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}

			$sqlupdateorder= "UPDATE `payomca_rms2`.`Order` SET `tableid`='$tableid',`situation`='$situation' WHERE `orderid`='$orderid';";
			$sqlupdateitem = "UPDATE `payomca_rms2`.`OrderContain` SET `itemid`='$itemid',`quantity`='$quantity',`comment`='$comment' WHERE `containid`='$containid';";
			mysqli_query($server, $sqlupdateorder);
			mysqli_query($server, $sqlupdateitem);
			
			echo "Order successfully updated!";
		}
		
	}

?>


</body>
</html>

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
	$abc = mysqli_fetch_row(mysqli_query($server, "SELECT * FROM `payomca_rms2`.`MenuItems` WHERE `Item ID`='$itemid';"));
	$itemname = $abc[1];
	$quantity = $thecontains[3];
	$comment = $thecontains[4];
	
	// Update values as the HTML fields are set by the user.
	isset($_REQUEST["situation"]) and $situation = $_REQUEST["situation"];
	isset($_REQUEST["tableid"]) and $tableid = $_REQUEST["tableid"];
	isset($_REQUEST["itemname"]) and $itemname = $_REQUEST["itemname"];
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
			<div><label>Order Status:</label> <select name="situation" value="<?php echo $situation; ?>">
			<?php	
				echo "<option value=\"$situation\" selected=\"selected\">$situation</option>";			
				echo "<option value=\"placed\">placed</option>";
				echo "<option value=\"ready\">ready</option>";
				echo "<option value=\"delivered\">delivered</option>";
				echo "<option value=\"done\">done</option>";
				echo "<option value=\"billed\">billed</option>";
			?>
			</select></div>
			
			<h2>Table</h2>
			<p>Change the table associated with this order.</p>
			<div><label>Table:</label> <select name="tableid" value=<?php echo $tableid; ?>>
			<?php				
				$sql1 = "SELECT * FROM `payomca_rms2`.`Tables`";	
				$res = mysqli_query($db, $sql1);
				echo "<option value=\"$tableid\" selected=\"selected\">$tableid</option>";
				while($row = $res->fetch_array(MYSQLI_NUM)){
					echo "<option value=\"$row[0]\">$row[0]</option>";
				}
			?>
			</select></div>
			
			<h2>Item</h2>
			<p>Change the ordered Item.</p>
			<div><label>Item Name:</label> <select name="itemname" value=<?php echo $itemname; ?>>
			<?php				
				$sql2 = "SELECT * FROM `payomca_rms2`.`MenuItems`";	
				$res = mysqli_query($db, $sql2);
				echo "<option value=\"$itemname\" selected=\"selected\">$itemname</option>";
				while($row = $res->fetch_array(MYSQLI_NUM)){
					echo "<option value=\"$row[1]\">$row[1]</option>";
				}
			?>
			</select></div>
			
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

		if (empty($situation) || empty($tableid) || empty($itemname) || empty($quantity)) {
			echo "Error: One or more required fields are empty. Only Comment can be empty.";
		} else if (strlen($comment) >= 300) {
			echo "Error: Comment is too large. Please limit comments to 300 characters.";
		}
		else {
			
			$server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms2");
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}

			$def = mysqli_fetch_row(mysqli_query($server, "SELECT * FROM `payomca_rms2`.`MenuItems` WHERE `Name`='$itemname';"));
			$newitemid = $def[0];
			
			$sqlupdateorder= "UPDATE `payomca_rms2`.`Order` SET `tableid`='$tableid',`situation`='$situation' WHERE `orderid`='$orderid';";
			$sqlupdateitem = "UPDATE `payomca_rms2`.`OrderContain` SET `itemid`='$newitemid',`quantity`='$quantity',`comment`='$comment' WHERE `containid`='$containid';";
			mysqli_query($server, $sqlupdateorder);
			mysqli_query($server, $sqlupdateitem);
			
			echo "Order successfully updated!";
		}
		
	}

?>


</body>
</html>

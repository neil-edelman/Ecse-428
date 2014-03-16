<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");

	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	
	// Generating a sequential OrderID for the new Order.
	$server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL during orderid generation: " . mysqli_connect_error();
	}
	$sql = "SELECT * FROM `payomca_rms`.`Order`";			
	$stmt = mysqli_prepare ($server, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $rows = mysqli_stmt_num_rows($stmt);
	if ($rows == 0) {
		$orderid = 1;
	} else {
		$orderid = ($rows + 1);
	}

	$tableid = null;
	echo $orderid;


	isset($_REQUEST["tableid"]) and $tableid = $_REQUEST["tableid"];	// When the tableid field is set, store in in $tableid.
	
	$tableupdate = null;

	
	
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
            <h1><font color="7700FF">Create Order</font></h1>

			<h2>Order's Table</h2>
			<p>Please enter the table associated with this order.</p>
			<div><label>Table of Order:</label> <input type="text" name="tableid"/></div>

			<p></p>
			<div><label></label><input type="submit" name="createorder" value="Create order"></div>
			
        </form>
		
		<p>Cancel and return to the <a href = "mainmenu.php">main menu</a>.</p>
		
		
		
<?php
	if(isset($_REQUEST["createorder"])) {
		if (!empty($tableid)) {
		
			$server= mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			
			$sql1 = "SELECT * FROM `payomca_rms`.`Tables` WHERE `tablenumber`='$tableid';";			
			$suchtables = mysqli_fetch_row(mysqli_query($server, $sql1));
			
			if ($suchtables != null ) {
				$sql2 = "INSERT INTO `payomca_rms`.`Order` (`orderid`, `tableid`, `situation`) VALUES ('$orderid', '$tableid', 'placed');";			
				mysqli_query($server, $sql2);

				$_SESSION['orderid'] = $orderid;	// Transferring $orderid to use on addtoorder.php
				header("Location: addtoorder.php");
			} else {
				echo "Error: This Table does not appear to exist.";
			}
			
		} else {
			echo "Error: No Table ID given.";
		}
	}
	
	// Allow user to update order.
	echo "Updating Orders: please input the table whose order you wish to update.";
	echo "<form method=\"post\"><div><label>Table of Order:</label> <input type=\"text\" name=\"tableupdate\"/></div>";
	echo "<p><input type=\"submit\" name=\"updateorder\" value=\"Update Order\"></form></p>\n\n";
	isset($_REQUEST["tableupdate"]) and $tableupdate = $_REQUEST["tableupdate"];
	
	if(isset($_REQUEST["updateorder"])) {
		if (empty($tableupdate)) {
			echo "Error: No table specified whose orders to edit.";
		} else {
			$sqlgetorder = "SELECT * FROM `payomca_rms`.`Order` WHERE `tableid`=$tableupdate;";
			$itsorders = mysqli_query($server, $sqlgetorder);	// This now stores all orders associated with the given table.
			$theorder = mysqli_fetch_row($itsorders);		// Get the 1st such order, just to see if there IS at least one.
			if ($theorder == null) {
				echo "This table has no orders associated with it.";
			} else {			
				$_SESSION['tableupdate'] = $tableupdate;	// Pass all this table to the next page.
				header("Location: chooseorder.php");
			}
		}
	}	

	?>
		<p></p>

</body>
</html>

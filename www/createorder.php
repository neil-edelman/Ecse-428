<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");

	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");

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

			<h2>Order Table</h2>
			<p>Please enter the table associated with this order.</p>
			<div><label>Table of Order:</label>
			<select name="tableid">
			<?php
			$sqlgetorder = "SELECT * FROM `payomca_rms2`.`Tables`";	
			$res = mysqli_query($db, $sqlgetorder);
			while($row = $res->fetch_array(MYSQLI_NUM)){
				echo "<option value=\"$row[0]\">$row[0]</option>";
			}
			?>
            </select></div>
			
			<p></p>
			<div><label></label><input type="submit" name="createorder" value="Create order"></div>
			
        </form>
		
		<p>Cancel and return to the <a href = "mainmenu.php">main menu</a>.</p>
		
		
		
<?php
	if(isset($_REQUEST["createorder"])) {
		if (!empty($tableid)) {

			$server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms2");
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
		
			$sql1 = "SELECT * FROM `payomca_rms2`.`Tables` WHERE `tablenumber`='$tableid';";
			$suchtables = mysqli_fetch_row(mysqli_query($server, $sql1));
			
			if ($suchtables != null ) {
				$sql2 = "INSERT INTO `payomca_rms2`.`Order` (`tableid`, `situation`) VALUES ('$tableid', 'placed');";			
				mysqli_query($server, $sql2);

				$a = mysqli_query($db, "SELECT MAX(orderid) FROM `payomca_rms2`.`Order`");	// Getting $orderid
				$b = mysqli_fetch_row($a);
				$orderid = $b[0];
				
				$_SESSION['orderidX'] = $orderid;
				
				header("Location: addtoorder.php");
			} else {
				echo "Error: This Table does not appear to exist.";
			}
			
		} else {
			echo "<br>Error: No Table ID given.<br>";
		}
	}
	
	// Allow user to update order.
	echo "Updating Orders: please input the table whose order you wish to update.";
	echo "<form method=\"post\"><div><label>Table of Order:</label> <select name=\"tableupdate\">";
	$sqlgetorder = "SELECT * FROM `payomca_rms2`.`Tables`";
	$res = mysqli_query($db, $sqlgetorder);
	while($row = $res->fetch_array(MYSQLI_NUM)){
		echo "<option value=\"$row[0]\">$row[0]</option>";
	}
	echo "</select></div>";
	echo "<p><input type=\"submit\" name=\"updateorder\" value=\"Update Order\"></form></p>\n\n";
	isset($_REQUEST["tableupdate"]) and $tableupdate = $_REQUEST["tableupdate"];
		
	if(isset($_REQUEST["updateorder"])) {
		if (empty($tableupdate)) {
			echo "Error: No table specified whose orders to edit.";
		} else {
			$sqlgetorder = "SELECT * FROM `payomca_rms2`.`Order` WHERE `tableid`=$tableupdate;";
			$itsorders = mysqli_query($db, $sqlgetorder);	// This now stores all orders associated with the given table.
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

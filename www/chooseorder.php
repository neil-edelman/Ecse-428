<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");

	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	
	$tableupdate = $_SESSION['tableupdate'];	// Getting the table from the previous page.

	// Let's count how many Orders exist for this Table.
	$server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms2");
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL during orderid generation: " . mysqli_connect_error();
	}		
	$sqlgetorder = "SELECT * FROM `payomca_rms2`.`Order` WHERE `tableid`=$tableupdate;";	
	$stmt = mysqli_prepare ($server, $sqlgetorder);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $countorders = mysqli_stmt_num_rows($stmt);		// Count how many Orders this Table has.
	
	// Let's get the Orders themselves.
	$itsorders = mysqli_query($server, $sqlgetorder);
	
	isset($_REQUEST["orderrank"]) and $orderrank = $_REQUEST["orderrank"];

?>
<!DOCTYPE html>

<html>
    <head>
        <meta charset="UTF-8">
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
		<link rel = "stylesheet" type = "text/css" href = "style.css">
		<title>Choose Order</title>
    </head>
    <body>

        <form method="post">
            <h1><font color="339933">Choose Order</font></h1>

			<h2>Select the Order</h2>
			<p>Please choose which Order associated with this table to update.</p>
			
			<?php
				echo "<p>The Table you selected has $countorders Orders associated with it. Which to update?</p>";
			?>
			
			<div><label>Order:</label>
			<select name="orderrank">
				<option value="X" disabled="disabled" selected="selected">Please select an Order</option>
                <?php
				$sqlgetorder = "SELECT * FROM `payomca_rms2`.`Order` WHERE `tableid`='$tableupdate'";	
				$res = mysqli_query($db, $sqlgetorder);
				while($row = $res->fetch_array(MYSQLI_NUM)){
					echo "<option value=\"$row[0]\">$row[0]</option>";
				}
				?>
            </select></div>

			<p></p>
			<div><label></label><input type="submit" name="select" value="Submit"></div>
			
        </form>
		
		<p>Cancel and return to the <a href = "mainmenu.php">main menu</a>.</p>
		
		
		
<?php
	if(isset($_REQUEST["select"])) {
		if (!empty($orderrank) && $orderrank!='X') {	
			$_SESSION['idorder'] = $orderrank;
			$_SESSION['itemnumber'] = 1;
			header("Location: updateorder.php");
		} else {
			echo "Error: You must select which order.";
		}
	}
?>
		<p></p>

</body>
</html>

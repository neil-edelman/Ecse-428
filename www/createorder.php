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
//	$orderid = uniqid();
	$tableid = null;
	echo $orderid;


	isset($_REQUEST["tableid"]) and $tableid = $_REQUEST["tableid"];	// When the tableid field is set, store in in $tableid.

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
			<div><label></label><input type="submit" value="Create order"></div>

        </form>

		<p>Cancel and return to the <a href = "viewpersonal.php">main menu</a>.</p>

<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!empty($tableid)) {

			$server= mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}

			$sql = "INSERT INTO `payomca_rms`.`Order` (`orderid`, `tableid`, `situation`) VALUES ('$orderid', '$tableid', 'placed');";
			mysqli_query($server, $sql);
			//echo $result;		// Life-saver for debugging.

			$_SESSION['orderid'] = $orderid;	// Transferring $orderid to use on addtoorder.php
			header("Location: addtoorder.php");
			//exit();

		} else {
			echo "Error: No Table ID given.";
		}
	}
	?>
		<p></p>

</body>
</html>

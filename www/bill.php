<?php
	
	include "session.php";	
	
	$s = new Session();
	
	$db = $s->link_database() or header_error("database error");
	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	// is_wait($info) or header_error("not authorised"); ?

	/* if the things are set, get them into vars */
	isset($_REQUEST["table"]) and $table  = strip_tags(stripslashes($_REQUEST["table"]));
	isset($_REQUEST["check"]) and is_array($_REQUEST["check"]) and $check = $_REQUEST["check"];

	/** hack to see wheater is checked; O(n) :[ can do better
	 @param check the value of the global var check (I have no idea why but it
	 doesn't work without it,) ie, array with the check values
	 @param value the order number that you want to see is checked or not
	 @return checked or not
	 @author Neil */
	function is_checked($check, $value) {
		if(!isset($check)) return false;
		foreach($check as $c) if($c == $value) return true;
		return false;
	}

?>
<!DOCTYPE html>

<html>
    <head>
		<meta charset="UTF-8">
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
		<link rel = "stylesheet" type = "text/css" href = "style.css">
		<title>Bill</title>
	</head>
	<body>

	<h1 class = "noprint">Bill</h1>

<?php
	if(!isset($table)) {
?>
<p><div>
<form>
<label>Table:</label>
<?php
	$s->select_things("Tables", "tablenumber", $no, "status", $status);
	echo "<select name = \"table\">\n";
	while($s->select_next()) {
		echo "<option value = \"".$no."\"";
		if($selected_no == $no) echo " selected";
		echo ">".$no." (".$status.")</option>\n";
	}
	echo "</select>\n";
?>
<br/>
<label>&nbsp;</label><input type = "submit" value = "Go">
</form>
</div></p>
<?php
	} else {
?>
<p class = "noprint">Table number <?php echo $table; ?>. Go <a href = "bill.php">back to table list</a>.</p>

<div>
<form>
<input type = "hidden" name = "table" value = "<?php echo $table; ?>">
<?php
		try {
			$order = $db->prepare("SELECT orderid "
								  ."FROM `Order` "
								  ."WHERE tableid = ? AND "
								  ."situation = 'done'"
								  ) or throw_exception("prepare order");
			$order->bind_param("i", $table) or throw_exception("binding order");
			$order->execute() or throw_exception("execute order");
			$order->store_result();
			$order->bind_result($orderid);

			$contain = $db->prepare("SELECT containid, itemid, quantity "
									."FROM OrderContain "
									."WHERE orderid = ?"
									) or throw_exception("prepare contain");
			$contain->bind_param("i", $orderid) or throw_exception("binding contain");

			$menu = $db->prepare("SELECT Name, Cost, `Item ID` "
								 ."FROM MenuItems "
								 ."WHERE `Item ID` = ? "
								 ."LIMIT 1") or throw_exception("prepare menu");
			$menu->bind_param("i", $item) or throw_exception("binding menu");

			while($order->fetch()) {

				// is it checked?
				$ordercheck = is_checked($check, $orderid);

				if($ordercheck) echo "<p>\n";
				else            echo "<p class = \"noprint\">\n";

				echo "<label>Order #".$orderid."</label>";
				if($ordercheck) echo "(checked) ";
				else echo "(unchecked) ";

				// provide the option to bill this on a new bill
				if(!$ordercheck) echo "<input type = \"checkbox\" name = \"check[]\" value = \"".$orderid."\"/>\n";
				echo "<br/>\n";
				if(!$ordercheck) echo "<label>Contains</label>\n";

				$contain->execute() or throw_exception("execute contain");
				$contain->store_result();
				$contain->bind_result($containid, $item, $quantity);

				while($contain->fetch()) {
					$menu->execute() or throw_exception("execute menu");
					$menu->store_result();
					$menu->bind_result($menuname, $menucost, $menuitemid);
					if($menu->fetch()) {
						echo $quantity." ".$menuname." at ".$menucost."; ";
						if($ordercheck) {
							// update bill and remove from order
						} else {
						}
					} else {
						echo $quantity." unknown \"".$item."\"; ";
					}
					$menu->free_result(); /* fixme */
				}

				echo "</p>\n";

				$contain->free_result(); /* fixme */
			}

			$order->free_result(); /* fixme */
			
			echo "<p>check param: ";
			if(isset($check)) {
				foreach($check as $c) {
					echo "contians ".$c."; ";
				}
			} else {
				echo "is not there";
			}
			echo "</p>\n";

		} catch(Exception $e) {
			echo "<p>Error: ".htmlspecialchars($e->getMessage()).".</p>\n";
		}
?>
<p class = "noprint">
<label>&nbsp;</label><input type = "submit" value = "Go"><br/>
<?php
		if(isset($check)) {
?>
<label>&nbsp;</label><input type = "button" value = "Print" onclick = "window.print()"><br/>
<?php
		}
?>
</p>
</form>
</div>
<?php
	}
?>

		<p class = "noprint">
			Go back to the <a href = "mainmenu.php">main menu</a>.
		</p>

	</body>

</html>

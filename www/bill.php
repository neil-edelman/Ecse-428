<?php
	
	include "session.php";	
	
	$s = new Session();
	
	$db = $s->link_database() or header_error("database error");
	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	// is_wait($info) or header_error("not authorised"); ?

	/* if the things are set, get them into vars */
	isset($_REQUEST["table"]) and $table  = strip_tags(stripslashes($_REQUEST["table"]));
	isset($_REQUEST["order"]) and is_array($_REQUEST["order"]) and $order = $_REQUEST["order"];

	function orderno($no) {
		// common functionality
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

<script type = "text/javascript">
<!--

/* this keeps track of who we're editing */
var editing = null;

/** swiches to editing
 @param a string which identifies the element
 @author Neil */
function edit(a) {
	if(editing) hide(editing);
	editing = a;

	var textStatic = document.getElementById(a);
	if(!textStatic) return;
	textStatic.style.display = "none";

	var textDynamic = document.getElementById(a.concat("edit"));
	if(!textDynamic) return;
	textDynamic.style.display = "block";
}

/** swiches back to looking
 @param a string which identifies the element
 @author Neil */
function hide(a) {

	var textStatic = document.getElementById(a);
	if(!textStatic) return;
	textStatic.style.display = "block";

	var textDynamic = document.getElementById(a.concat("edit"));
	if(!textDynamic) return;
	textDynamic.style.display = "none";

	editing = null;
}

// -->
</script>

	</head>
	<body>

	<h1>Bill</h1>

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
	} else if(!isset($order)) {
?>
<p>Table number <?php echo $table; ?>. Go <a href = "bill.php">back to table list</a>.</p>
<p><div>
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
				echo "<label>Order #".$orderid."</label>";
				echo "<input type = \"checkbox\" name = \"order[]\" value = \"".$orderid."\"/><br/>\n";
				echo "<label>Contains</label>";
				$contain->execute() or throw_exception("execute contain");
				$contain->store_result();
				$contain->bind_result($containid, $item, $quantity);

				while($contain->fetch()) {
					$menu->execute() or throw_exception("execute menu");
					$menu->store_result();
					$menu->bind_result($menuname, $menucost, $menuitemid);
					if($menu->fetch()) {
						echo $quantity." ".$menuname." at ".$menucost."; ";
					} else {
						echo $quantity." unknown \"".$item."\"; ";
					}
					$menu->free_result(); /* fixme */
				}

				echo "<br/>\n";

				$contain->free_result(); /* fixme */
			}

			$order->free_result(); /* fixme */

		} catch(Exception $e) {
			echo "<p>Error: ".htmlspecialchars($e->getMessage()).".</p>\n";
		}
?>
<br/>
<label>&nbsp;</label><input type = "submit" value = "Go">
</form>
</div></p>
<?php
	} else {
		echo "<p>".var_dump($order)."</p>\n";
	}
?>

		<p>
			Go back to the <a href = "mainmenu.php">main menu</a>.
		</p>

	</body>

</html>

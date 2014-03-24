<?php
	
	include "session.php";	
	
	$s = new Session();
	
	$db = $s->link_database() or header_error("database error");
	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	is_admin($info) or header_error("not authorised");

	/* if the things are set, get them into vars */
	isset($_REQUEST["subject"])     and $subject     = strip_tags(stripslashes($_REQUEST["subject"]));
	isset($_REQUEST["newcheckin"])  and $newcheckin  = strip_tags(stripslashes($_REQUEST["newcheckin"]));
	isset($_REQUEST["newcheckout"]) and $newcheckout = strip_tags(stripslashes($_REQUEST["newcheckout"]));
?>
<!DOCTYPE html>

<html>
    <head>
		<meta charset="UTF-8">
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
		<link rel = "stylesheet" type = "text/css" href = "style.css">
		<title>Shifts</title>

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

/** "deletes" the value (sets it to hidden)
 (changed the ordering so this is not needed)
 @param a string which identifies the element
 @author Neil */
/*function del(a) {

	var textStatic = document.getElementById(a);
	if(textStatic) textStatic.style.display = "none";

	var textDynamic = document.getElementById(a.concat("edit"));
	if(textDynamic) textDynamic.style.display = "none";

	// I don't think this matters; editing = null;
}*/

// -->
</script>

	</head>
	<body>

	<h1>Shifts</h1>

<p><div>
<form>
<label>View shifts of</label>
<!-- input user -Neil; this is lame -Neil
	<input type="text" name="subject"
	value = "<?php if(isset($subject)) echo $subject;?>"
	maxlength = "<?php echo Session::USERNAME_MAX;?>"/><br/><br/>
-->
<?php
	/* this is less lame, but still lame -Neil
	if(isset($subject)) {
		$s->select_users("subject", $subject);
	} else {
		$s->select_users("subject");
	}*/
	/* this is awesome */
	$s->select_things("Users", "username", $username, "FirstName", $first, "LastName", $last);
	echo "<select name = \"subject\">\n";
	while($s->select_next()) {
		echo "<option value = \"".$username."\"";
		if($subject == $username) echo " selected";
		echo ">".$last.", ".$first."</option>\n";
	}
	echo "</select>\n";
?>
<br/>
<label>&nbsp;</label><input type = "submit" value = "Go">
</form>
</div></p>

<?php
	if(isset($subject)) {
		try {
			$subjectinfo = $s->user_info($subject) or throw_exception("user info error");
			echo "<p>".$subjectinfo["LastName"].", ".$subjectinfo["FirstName"]
				." is a(n) ".$subjectinfo["Privilege"].".</p>\n";
			if($subjectinfo["checkin"]) {
				echo "<p>They are currently working.</p>\n";
			}

			/* prepare statements */
			$in = $db->prepare("UPDATE Shifts SET checkin = ? "
							   ."WHERE id = ? "
							   ."LIMIT 1") or throw_exception("prepare checkin");
			$in->bind_param("si", $datetime, $id) or throw_exception("binding checkin");
			$out = $db->prepare("UPDATE Shifts SET checkout = ? "
								."WHERE id = ? "
								."LIMIT 1") or throw_exception("prepare checkout");
			$out->bind_param("si", $datetime, $id) or throw_exception("binding checkout");
			$del = $db->prepare("DELETE FROM Shifts "
								."WHERE id = ? "
								."LIMIT 1") or throw_exception("prepare delete");
			$del->bind_param("i", $id) or throw_exception("binding delete");

			/* values submitted? */
			foreach($_REQUEST as $k => $v) {
				/* this is really ugly */
				if(strncmp("shifts", $k, 6)) continue;
				list($number, $action) = sscanf($k, "shifts%uedit-%s");
				//echo "<p>".$k." -&gt; ".$v." (".$number." ".$action.")</p>\n";
				/* prepare */
				$datetime = iso2db($v);
				$id       = $number;
				/* exec */
				if($action === "checkin") {
					$in->execute() or throw_exception("execute checkin, set ".$id." to ".$datetime);
					echo "<p>Changed ".$id." checkin to ".$datetime.".</p>\n\n";
				} else if($action === "checkout") {
					$out->execute() or throw_exception("execute checkout, set ".$id." to ".$datetime);
					echo "<p>Changed ".$id." checkout to ".$datetime.".</p>\n\n";
				} else if($action === "delete") {
					$del->execute() or throw_exception("execute delete ".$id);
					echo "<p>Deleted ".$id.".</p>\n\n";
					// javascipt in html in php in html
					//echo "<script type = \"text/javascript\">\n<!--\ndel(\"shifts\".concat($number));\n// -->\n</script>\n\n";
				}
			}

			/* new shift? */
			if(isset($newcheckin) && isset($newcheckout)) {
				if($s->add_shift($subject, $newcheckin, $newcheckout)) {
					echo "<p>Shift was added.</p>\n\n";
				} else {
					echo "<p>Error: ".$s->status()."</p>\n\n";
				}
			}
		} catch(Exception $e) {
			echo "<p>Error: ".htmlspecialchars($e->getMessage()).".</p>\n";
		}

		echo "<p>\n";
		$s->view_shifts($subject);
		echo "</p>\n";
?>
<p><div><form>
<input type = "hidden" name = "subject" value = "<?php echo $subject; ?>">
<label>Check in:</label><input type = "datetime" name = "newcheckin"
value = "<?php if(isset($newcheckin)) echo $newcheckin;?>"/><br/>
<label>Check out:</label><input type = "datetime" name = "newcheckout"
value = "<?php if(isset($newcheckout)) echo $newcheckout;?>"/><br/>
<label>&nbsp;</label><input type = "submit" value = "New">
</form>
</div></p>
<?php
	} else {
		echo "<p>No subject entered.</p>\n";
	}
?>

		<p>
			Go back to the <a href = "mainmenu.php">main menu</a>.
		</p>

	</body>

</html>

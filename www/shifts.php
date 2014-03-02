<?php
	
	include "session.php";	
	
	$s = new Session();
	
	$db = $s->link_database() or header_error("database error");
	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	is_admin($info) or header_error("not authorised");

	/* if the things are set, get them into vars */
	isset($_REQUEST["subject"])  and $subject = strip_tags(stripslashes($_REQUEST["subject"]));

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
	textDynamic.style.display = "inline";
}

/** swiches back to looking
 @param a string which identifies the element
 @author Neil */
function hide(a) {

	var textStatic = document.getElementById(a);
	if(!textStatic) return;
	textStatic.style.display = "inline";

	var textDynamic = document.getElementById(a.concat("edit"));
	if(!textDynamic) return;
	textDynamic.style.display = "none";

	editing = null;
}

// -->
</script>

	</head>
	<body>

	<h1>Shifts</h1>

<div>
<form>
<label>View shifts of</label>
<!-- input user -Neil; this is lame -Neil
	<input type="text" name="subject"
	value = "<?php if(isset($subject)) echo $subject;?>"
	maxlength = "<?php echo Session::USERNAME_MAX;?>"/><br/><br/>
-->
<?php
	if(isset($subject)) {
		$s->select_users("subject", $subject);
	} else {
		$s->select_users("subject");
	}
?>
<input type = "submit" value = "Go"><br/><br/>
</form>
</div>

<?php
	if(isset($subject)) {
		try {
			$subjectinfo = $s->user_info($subject) or throw_exception("user info error");
			echo "<p>".$subjectinfo["LastName"].", ".$subjectinfo["FirstName"]
				." is a(n) ".$subjectinfo["Privilege"].".</p>\n";
			if($subjectinfo["checkin"]) {
				echo "<p>They are currently working.</p>\n";
			}
			echo "<p>\n";
			$s->view_shifts($subject);
			echo "</p>\n";

			/* prepare statements */
			$in = $db->prepare("UPDATE Shifts SET checkin = ? "
							   ."WHERE id = ? "
							   ."LIMIT 1") or throw_exception("prepare in");
			$in->bind_param("si", $datetime, $id) or throw_exception("binding in");
			$out = $db->prepare("UPDATE Shifts SET checkout = ? "
								."WHERE id = ? "
								."LIMIT 1") or throw_exception("prepare out");
			$out->bind_param("si", $datetime, $id) or throw_exception("binding out");

			/* values submitted? */
			foreach($_REQUEST as $k => $v) {
				/* this is really ugly */
				if(strncmp("shifts", $k, 6)) continue;
				list($number, $check) = sscanf($k, "shifts%uedit-%s");
				echo "<p>".$k." -&gt; ".$v." (".$number." ".$check.")</p>\n";
				/* prepare */
				$datetime = iso2db($v);
				$id       = $number;
				echo "Id ".$id." to ".$datetime." -&gt; ".iso2db($datetime)."<br/>\n";
				/* exec */
				if($check === "checkin") {
					$in->execute() or throw_exception("execute in");
					echo "changed ".$id." checkin to ".$datetime.".<br/>\n";
				} else if($check === "checkout") {
					$out->execute() or throw_exception("execute out ".$id.";".$datetime);
					echo "changed ".$id." checkout to ".$datetime.".<br/>\n";
				}
				///////// delete!!! ///////////////
			}
		} catch(Exception $e) {
			echo "<p>Error: ".$e->getMessage().".</p>\n";
		}
	} else {
		echo "<p>No subject entered.</p>\n";
	}
?>

		<p>
			Go back to the <a href = "mainmenu.php">main menu</a>.
		</p>

	</body>

</html>

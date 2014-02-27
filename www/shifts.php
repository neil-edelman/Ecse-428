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
	</head>
	<body>

	<h1>Shifts</h1>

<div>
<form>
<label>View shifts of</label>
<input type="text" name="subject"
value = "<?php if(isset($subject)) echo $subject;?>"
maxlength = "<?php echo Session::USERNAME_MAX;?>"/><br/><br/>
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

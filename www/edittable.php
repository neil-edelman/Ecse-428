<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");
	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	is_admin($info) or header_error("not authorised");

	/* Save the original table value from the POST of the source page */
	isset($_POST['intable']) and $_SESSION["oritable"] = $_POST['intable'] and $tablenumber = $_SESSION["oritable"];
	isset($_POST['inmaxsize']) and $_SESSION["orimaxsize"]	= $_POST['inmaxsize'];
	isset($_POST['incurrsize']) and $_SESSION["oricurrentsize"]	= $_POST['incurrsize'];
	isset($_POST['instatus']) and $_SESSION["oristatus"]	= $_POST['instatus'];

        if(isset($_SESSION['submitted'])){
            $submitted = $_SESSION['submitted'];
            unset($_SESSION['submitted']);
	}

			/* if the things are set, get them into vars */
	isset($_REQUEST["tablenumber"])	and $tablenumber 	= strip_tags(stripslashes($_REQUEST["tablenumber"]));
	isset($_REQUEST["maxsize"]) 	and $maxsize    	= strip_tags(stripslashes($_REQUEST["maxsize"]));
	isset($_REQUEST["currentsize"])	and $currentsize   	= strip_tags(stripslashes($_REQUEST["currentsize"]));
	isset($_REQUEST["status"])     	and $status			= strip_tags(stripslashes($_REQUEST["status"]));

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
		<link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>Edit Table</title>
    </head>
	    <body>
        <form method="post">
            <h1>Edit Table</h1>
			<label>Currently: <br/></label>
			<?php
			echo "Previous Table Number: &quot;".$_SESSION["oritable"][0]."&quot;<br/>\n";
			?>

            <div>
			<label>New Table ID:</label>
<input type="text" name="tablenumber"
value = "<?php if(isset($tablenumber)) echo $tablenumber;?>"
maxlength = "<?php echo Session::INTEGER_MAX;?>"/><br/>

            <label>New Table Maximum Size:</label>
<input type="text" name="maxsize"
value = "<?php if(isset($maxsize)) echo $maxsize;?>"
maxlength = "<?php echo Session::INTEGER_MAX;?>"/><br/>

            <label>New Table Size:</label>
<input type="text" name="currentsize"
value = "<?php if(isset($currentsize)) echo $currentsize;?>"
maxlength = "<?php echo Session::INTEGER_MAX;?>"/><br/>

            <br/>
            <br/>
			<input type = "submit" value = "Edit" <?php if (!isset($_SESSION["oritable"])){ echo "disabled";} ?>/>
			<br/>
			<input type = "reset" value = "Reset"/>
			<br/>
			<p><?php if(isset($submitted)) echo "Edit Complete.";?><br/>
			<?php if (!isset($_SESSION["oritable"])){ echo "This page is presently stale.  Please return to mainmenu";} ?></p>
			<p>
			Go back to <a href = "viewtables.php">view table</a>.
			</p>
			</div>
        </form>

		<?php
			$is_ready = false;
			if(   isset($tablenumber)
			   || isset($maxsize)
			   || isset($currentsize)){
				$is_ready = true;
				if(   !isset($tablenumber)
				   || !isset($maxsize)
				   || !isset($currentsize)
				   || empty($tablenumber)
				   || empty($maxsize)
				   || (empty($currentsize) && $currentsize != 0)) {
					$is_ready = false;
					echo "You did not enter all the required information.<br/>\n";
				}
				if(strlen($tablenumber) > Session::INTEGER_MAX) {
					$is_ready = false;
					echo "Username is maximum ".Session::INTEGER_MAX." characters.<br/>\n";
				}
				if(strlen($maxsize) > Session::INTEGER_MAX) {
					$is_ready = false;
					echo "Password is too long.<br/>\n";
				}
				if(strlen($currentsize) > Session::INTEGER_MAX) {
					$is_ready = false;
					echo "First name is maximum ".Session::INTEGER_MAX." characters.<br/>\n";
				}
				if($currentsize > $maxsize){
					$is_ready = false;
					echo "current size is larger than the maximum size.";
				}else if($currentsize > 0){
					$status = 'occupied';
				}else{
					$status = 'vacant';
				}

			}
			if($is_ready) {
				if($s->edit_table($_SESSION["oritable"], $tablenumber, $maxsize, $currentsize, $status)){

					/* Clear out temporary Session variables*/
					unset($_SESSION['oritable']);
					unset($_SESSION['orimaxsize']);
					unset($_SESSION['oricurrentsize']);
					unset($_SESSION['oristatus']);
					$_SESSION['submitted'] = true;

					Header('Location: '.$_SERVER['PHP_SELF']);
				} else {
					echo "Table not edited: ".$s->status()."<br/>\n";
				}
			}
        ?>

	</body>


</html>

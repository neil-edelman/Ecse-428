<?php

	include "session.php";
	
	$s = new Session();

	$db = $s->link_database() or header_error("database error");
	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	//only administrators can create table?
	is_admin($info) or header_error("not authorised");

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
        <title>Create Table</title>
    </head>
	    <body>
        <form method="post">
            <h1>Add a new table</h1>
            <div>
			<label>Table ID:</label>
<input type="text" name="tablenumber"
value = "<?php if(isset($tablenumber)) echo $tablenumber;?>" 	
maxlength = "<?php echo Session::INTEGER_MAX;?>"/><br/>

            <label>Table Maximum Size:</label>
<input type="text" name="maxsize"
value = "<?php if(isset($maxsize)) echo $maxsize;?>" 
maxlength = "<?php echo Session::INTEGER_MAX;?>"/><br/>

            <label>Current Table Size:</label>
<input type="text" name="currentsize"
value = "<?php if(isset($first)) echo $first;?>"  
maxlength = "<?php echo Session::INTEGER_MAX;?>"/><br/>

            <label>Status:</label>
            <select name="status">
                <option <?php if(isset($status)) echo $status=="vacant"?"selected ":"";?>value="vacant">vacant</option>
                <option <?php if(isset($status)) echo $status=="occupied"?"selected ":"";?>value="occupied">occupied</option>
            </select>
            <br/>
            <br/>
			<input type = "submit" value = "New"/>
			<br/>
			<input type = "reset" value = "Reset"/>
			<p>
			Go back to <a href = "mainmenu.php">mainmenu</a>.
			</p>
			</div>
        </form>
		
		<?php
			$is_ready = false;
			if(   isset($tablenumber)
			   || isset($maxsize)
			   || isset($currentsize)
			   || isset($status)){
				$is_ready = true;
				if(   !isset($tablenumber)
				   || !isset($maxsize)
				   || !isset($currentsize)
				   || !isset($status)
				   || empty($tablenumber)
				   || empty($maxsize)
				   || (empty($currentsize) && $currentsize != 0)
				   || empty($status)) {
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
			}
			if($is_ready) {
				if($s->new_table($tablenumber, $maxsize, $currentsize, $status)) {
					echo "Table &quot;".$tablenumber."&quot; created.<br/>\n";
				} else {
					echo "Table not created: ".$s->status()."<br/>\n";
				}
			}
        ?>
		
	</body>
	
	
</html>
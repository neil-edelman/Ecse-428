<?php

	include "session.php";
	
	$s = new Session();

	$db = $s->link_database() or header_error("database error");
	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	is_admin($info) or header_error("not authorised");
	
	/* Save the original table value from the POST of the source page */
	isset($_POST['username']) and $_SESSION["oriusername"] = $_POST['username'] and $username = $_SESSION["oriusername"];
	if(isset($_SESSION['submitted'])){
		$submitted = $_SESSION['submitted'];
		unset($_SESSION['submitted']);
	}
	
	/* if the things are set, get them into vars */
	isset($_REQUEST["username"])	and $username 		= strip_tags(stripslashes($_REQUEST["username"]));
	isset($_REQUEST["password"]) 	and $password    	= strip_tags(stripslashes($_REQUEST["password"]));
	isset($_REQUEST["first"])   and $first		= strip_tags(stripslashes($_REQUEST["first"]));
	isset($_REQUEST["last"])	and $last   	= strip_tags(stripslashes($_REQUEST["last"]));
	isset($_REQUEST["email"])   	and $email			= strip_tags(stripslashes($_REQUEST["email"]));
	isset($_REQUEST["privilege"])   and $privilege		= strip_tags(stripslashes($_REQUEST["privilege"]));

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
		<link rel = "stylesheet" type = "text/css" href = "style.css">
        <title>Edit User</title>
    </head>
	    <body>
		
		<form method="post">
            <h1>Edit User</h1>
			<label>Currently: <br/></label>
			<?php
			echo "Previous User username: &quot;".$_SESSION["oriusername"]."&quot;<br/>\n";
			?>
			
            <div>
			<label>New Username:</label>
			<input type="text" name="username"
			value = "<?php if(isset($username)) echo $username;?>" 	
			maxlength = "<?php echo Session::USERNAME_MAX;?>"/><br/>

            <label>New Password:</label>
			<input type="text" name="password"
			value = "<?php if(isset($password)) echo $password;?>" 
			maxlength = "<?php echo Session::PASSWORD_MAX;?>"/><br/>

            <label>New First Name:</label>
			<input type="text" name="first"
			value = "<?php if(isset($first)) echo $first;?>"  
			maxlength = "<?php echo Session::FIRST_MAX;?>"/><br/>

            <label>New Last Name:</label>
			<input type="text" name="last"
			value = "<?php if(isset($last)) echo $last;?>"
			maxlength = "<?php echo Session::LAST_MAX;?>"/><br/>
			
            <label>Email:</label>
			<input type="text" name="email"
			value = "<?php if(isset($email)) echo $email;?>"
			maxlength = "<?php echo Session::EMAIL_MAX;?>"/><br/>
			
            <label>Privilege:</label>
            <select name="privilege">
                <option <?php if(isset($privilege)) echo $privilege=="wait"?"selected ":"";?>value="wait">Wait Staff</option>
                <option <?php if(isset($privilege)) echo $privilege=="cook"?"selected ":"";?>value="cook">Cook Staff</option>
                <option <?php if(isset($privilege)) echo $privilege=="manager"?"selected ":"";?>value="manager">Manager</option>
                <option <?php if(isset($privilege)) echo $privilege=="admin"?"selected ":"";?>value="admin">System Admin</option>
            </select>
            
			<br/>
            <br/>
			<input type = "submit" value = "Edit" <?php if (!isset($_SESSION["oriusername"])){ echo "disabled";} ?>/>
			<br/>
			<input type = "reset" value = "Reset"/>
			<br/>
			<p><?php if(isset($submitted)) echo "Edit Complete.";?><br/>
			<?php if (!isset($_SESSION["oriusername"])){ echo "This page is presently stale.  Please return to mainmenu";} ?></p>
			<p>
			Go back to <a href = "viewusers.php">View Users</a>.
			</p>
			</div>
        </form>		
		
		<?php
			$is_ready = false;
			if(   isset($username)
			   || isset($password)
			   || isset($first)
			   || isset($last)
			   || isset($email)
			   || isset($privilege)) {
				$is_ready = true;
				if(   !isset($username)
				   || !isset($password)
				   || !isset($first)
				   || !isset($last)
				   || !isset($email)
				   || !isset($privilege)
				   || empty($username)
				   || empty($password)
				   || empty($first)
				   || empty($last)
				   || empty($email)
				   || empty($privilege)) {
					$is_ready = false;
					echo "You did not enter all the required information.<br/>\n";
				}
				if(strlen($username) > Session::USERNAME_MAX) {
					$is_ready = false;
					echo "Username is maximum ".Session::USERNAME_MAX." characters.<br/>\n";
				}
				if(strlen($password) > Session::PASSWORD_MAX) {
					$is_ready = false;
					echo "Password is too long.<br/>\n";
				}
				if(strlen($first) > Session::FIRST_MAX) {
					$is_ready = false;
					echo "First name is maximum ".Session::FIRST_MAX." characters.<br/>\n";
				}
				if(strlen($last) > Session::LAST_MAX) {
					$is_ready = false;
					echo "Last name is maximum ".Session::LAST_MAX." characters.<br/>\n";
				}
				if(strlen($email) > Session::EMAIL_MAX) {
					$is_ready = false;
					echo "E-mail is maximum ".Session::EMAIL_MAX." characters.<br/>\n";
				}else if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)){
					$is_ready = false;
					echo "Not a valid email format.<br/>\n";
				}
			}
			if($is_ready) {
				if($s->edit_user($_SESSION['oriusername'], $username, $password, $first, $last, $email, $privilege)) {
					
					/* Clear out temporary Session variables*/
					unset($_SESSION['oriusername']);

					$_SESSION['submitted'] = true;
					
					Header('Location: '.$_SERVER['PHP_SELF']);
				} else {
					echo "Account not edited: ".$s->status()."<br/>\n";
				}
			}
        ?>
		
		
		
		</body>
	
	
</html>
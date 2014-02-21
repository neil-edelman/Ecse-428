<?php

	include "session.php";
	
	$s = new Session();

	$db = $s->link_database() or header_error("database error");
	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	is_admin($info) or header_error("not authorised");

	$tablenumber = $_POST['tablenumber'];
	$maxsize = $_POST['maxsize'];
	$currentsize = $_POST['currentsize'];
	$status = $_POST['status'];
	
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
        <form method="post" action="catching-var.php">
		
		1. <input type="text" name = "name1"/><br/>
		2. <input type="text" name = "name2"/><br/>
		3. <input type="text" name = "name3"/><br/>
		4. <input type="text" name = "name4"/><br/>
		
		<input type="submit" name="submit"/>
		
		</form>
		</body>
</html>
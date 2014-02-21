<?php

	include "session.php";
	
	$s = new Session();

	$db = $s->link_database() or header_error("database error");
	$user = $s->get_user() or header_error("user timeout error");
	$info = $s->user_info($user) or header_error("user info error");
	is_admin($info) or header_error("not authorised");

	/* if the things are set, get them into vars */
	isset($_REQUEST["username"])  and $username = strip_tags(stripslashes($_REQUEST["username"]));
	isset($_REQUEST["password"])  and $_REQUEST["password"] != "" and $password = $_REQUEST["password"];
	isset($_REQUEST["firstname"]) and $first    = strip_tags(stripslashes($_REQUEST["firstname"]));
	isset($_REQUEST["lastname"])  and $last     = strip_tags(stripslashes($_REQUEST["lastname"]));
	isset($_REQUEST["email"])     and $email    = strip_tags(stripslashes($_REQUEST["email"]));
	isset($_REQUEST["privilege"]) and $privilege= strip_tags(stripslashes($_REQUEST["privilege"]));

?>
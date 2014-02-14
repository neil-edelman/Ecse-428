<?php

	include "session.php";

	$s = new Session();

	$db = $s->link_database() or header_error("database error");
	if($s->get_user()) {
		header("Location: mainmenu.php");
	} else {
		header("Location: loginpage.php");
	}

?>

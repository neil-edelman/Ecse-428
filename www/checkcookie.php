<?php
	$mycookie = $_COOKIE['superCookie'];
	setcookie("superCookie", "", time()-3600);
	$server = mysqli_connect("localhost","payomca_rms","mushroom","payomca_rms");
	$cookie_query = "SELECT * FROM SessionID WHERE ID = '$mycookie';";   
	$query_result = mysqli_query($server, $cookie_query);
	if (mysqli_num_rows($query_result) == 1) {
		$thecookie = mysqli_fetch_row($query_result);
		$username = $thecookie[1];	// Use this in your code
		$privilege = $thecookie[2];	// Use this in your code
	}
	else {
		echo "Critical Failure";
		die();
	}
?>
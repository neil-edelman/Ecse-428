<?php

	/** checks all the login stuff */
	function login($db) {

		$query  = "SELECT * FROM session WHERE session_id='".session_id()."'";
		$result = $db->query($query);

		/* this session exists */
		if($result->num_rows != 1) {
			header("Location: index.php?message=NotLoggedIn");
			return;
		}
		$entry = $db->fetch_array($result);

		/* it's for this ip */
		/* fixme */

		/* it's not expired */
		/* fixme */

		$result->close();

		return -1;
	}

?>

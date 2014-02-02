<?php
// page2.php

session_start();

echo 'Welcome to page #2<br />';


// Echo all the things stored within the session.
	echo '<br />';
echo $_SESSION['username'];
	echo '<br />';
echo $_SESSION['number'];
	echo '<br />';
echo date('Y m d H:i:s', $_SESSION['time']);


// Apparently closing the session now has some trippy side effects.
//session_close();


	echo '<br />';
// Return to page 1 with this link.
echo '<a href="page1.php">page 1</a>';

	echo '<br />';
echo '<a href="session.html">Return to session page</a>';
?>
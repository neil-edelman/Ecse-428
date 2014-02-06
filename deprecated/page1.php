<?php
// page1.php

session_start();

echo 'Welcome to page #1';


// Let's store the username of the person who just logged in.
// **Sort of. Right now it's just a random name.
$_SESSION['username'] = 'Captain Oblivious';

// Let's store something else. A random number.
$_SESSION['number']   = 75;

// Let's store the time at which the session was created (more likely the time the next line is read).
$_SESSION['time']     = time();


// Apparently closing the session now has some trippy side effects.
//session_close();


	echo '<br />';
// Works if session cookie was accepted
echo '<a href="page2.php">page 2</a>';

	echo '<br />';
// Supposedly this sends the SID if the link is clicked.
echo "Supposedly this 2nd button sends the SID to page 2 if pressed. From my tests it doesn't quite do anything here.";
echo '<a href="page2.php?' . SID . '">page 2</a>';

	echo '<br />';
echo '<a href="session.html">Return to session page</a>';
?>
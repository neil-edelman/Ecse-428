<?php
    include "session.php";
    include "modifytables.php";    
	
    $s = new Session();
    $g = new modifytables();
	
    $db = $s->link_database() or header_error("database error");
    $user = $s->get_user() or header_error("user timeout error");
    $table_number  = $_POST["intable"];  
    
    
    // check if there are 'unpaid' orders associated to table
    $g->clear_table($db, $table_number);    
    
    header('Location: viewtables.php');
    die();
      
?>


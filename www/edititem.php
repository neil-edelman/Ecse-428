<?php
    include "session.php";
    include "modifyitems.php";
	
    $s = new Session();
    $m = new modifyitems();

    $db = $s->link_database() or header_error("database error");
    $user = $s->get_user() or header_error("user timeout error");
    $info = $s->user_info($user) or header_error("user info error");
    is_admin($info) or header_error("not authorised");
    
    if (isset($_POST["initem"])) {
        $item_number  = $_POST["initem"];
        $_SESSION['item_number'] = $item_number;
        $info = $m->get_item_info($item_number, $db);
        while ($row = $info->fetch_array(MYSQLI_NUM)) {
           $info1 = $row[1]; 
           $info2 = $row[2]; 
           $info3 = $row[3]; 
        }
    }
            
    isset($_REQUEST["itemid"]) and $itemid = strip_tags(stripslashes($_REQUEST["itemid"])) and $item_number = $itemid;
    isset($_REQUEST["itemname"]) and $itemname = strip_tags(stripslashes($_REQUEST["itemname"])) and  $info1= $itemname;
    isset($_REQUEST["itemcost"]) and $itemcost = strip_tags(stripslashes($_REQUEST["itemcost"])) and $info2 = $itemcost;
    isset($_REQUEST["description"]) and $description = strip_tags(stripslashes($_REQUEST["description"])) and $info3 = $description;      
    
    $is_ready = true;	
    if(!isset($itemid) || !isset($itemname) || !isset($itemcost) || !isset($description)
	|| empty($itemid) || empty($itemname) || empty($itemcost) || empty($description)) {
        $is_ready = false;
        echo "You must fill up all fields before clicking Edit.<br/>\n";
    }
    if(strlen($itemid) > Session::INTEGER_MAX) {
        $is_ready = false;
        echo "Item ID is maximum ".Session::INTEGER_MAX." characters.<br/>\n";
    }
    if(strlen($itemname) > Session::NAME_MAX) {
        $is_ready = false;
        echo "Item Name is too long.<br/>\n";
    }
    if(strlen($itemcost) > Session::INTEGER_MAX) {
        $is_ready = false;
        echo "Item Cost is maximum ".Session::INTEGER_MAX." characters.<br/>\n";
    }
    if(strlen($description) > Session::DESCRIPTION_MAX) {
        $is_ready = false;
        echo "Item descrition is maximum ".Session::INTEGER_MAX." characters.<br/>\n";
    }
   
    if($is_ready) {
        if (!isset($item_number)) {
            $item_number =  $_SESSION['item_number'];
            unset($_SESSION['item_number']);
        }
        if($m->edit_item($item_number, $itemid, $itemname, $itemcost, $description, $db)) {            
            $edit_complete = true;       
            unset($_SESSION['item_number']);
        } else {
            echo "Item not created: ".$m->status()."<br/>\n";
        }         
    }
    
   //echo "ddddd: " .  $_SESSION['item_number'];
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
		<meta name = "Author" content = "Team RMS">
		<link rel = "shortcut icon" href = "favicon.ico" type = "image/x-icon">
                <link rel = "stylesheet" type = "text/css" href = "style.css">
		<title>Create New Item</title>
    </head>
    <body>
       <form method="post" action="edititem.php">            
            <div>
                <h1>Edit Item</h1>                    
                
                <label>New Item ID:</label>
                <input type="text" name="itemid"
                    value = "<?php if(isset($item_number)) echo $item_number;?>" 	
                    maxlength = "<?php echo Session::INTEGER_MAX;?>"/><br/>

                <label>New Item Name:</label>
                <input type="text" name="itemname"
                    value = "<?php if(isset($info1)) echo $info1;?>" 	
                    maxlength = "<?php echo Session::NAME_MAX;?>"/><br/>            
                
                 <label>New Item Cost:</label>
                <input type="text" name="itemcost"
                    value = "<?php if(isset($info2)) echo $info2;?>"  
                    maxlength = "<?php echo Session::INTEGER_MAX;?>"/><br/>

                <label>New Item Description:</label>
                <input type="text" name="description"
                    value = "<?php if(isset($info3)) echo $info3;?>"  
                    maxlength = "<?php echo Session::DESCRIPTION_MAX;?>"/>
                <br/><br/><br/>   
                
                <input type = "submit" value = "Edit" <?php if (isset($edit_complete) || !isset($_SESSION['item_number'])) { echo "disabled";}?>/>
                <p><?php if(isset($edit_complete)) echo "Edit Complete. Please Navigate back to View Items";?><br/>                
                <p>
                    Go back to <a href = "viewitems.php">View Items</a>.
		</p>
           </div>
       </form>        
    </body>
</html>
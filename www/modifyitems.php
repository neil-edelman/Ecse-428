<?php
/**
 * Description of modifyitems
 *
 * @author jonathan
 */
    class modifyitems {
        private $status = "okay";
        
        final public function new_item($itemid, $itemname, $itemcost, $description, $db){		
            $created = null;
            try {
		$stmt = $db->prepare("INSERT INTO `MenuItems` (`Item ID`, `Name`, `Cost`, `Description`) "
									 ."VALUES (?, ?, ?, ?)") or throw_exception("prepare");
		$stmt->bind_param("isis", $itemid, $itemname, $itemcost, $description) or throw_exception("binding");
		$stmt->execute() or throw_exception("execute");
		$created = $itemname;
            } catch(Exception $e) {
		$errno = ($stmt ? $stmt->errno : $db->errno);
		$error = ($stmt ? $stmt->error : $db->error);
		$this->status = "new_item ".$e->getMessage()." failed: (".$errno.") ".$error;
            }
            $stmt and $stmt->close();

            return $created;			
	}
        
        final public function edit_item($item_number, $itemid, $itemname, $itemcost, $description, $db){
            $created = null;
            try {
		$stmt = $db->prepare("UPDATE MenuItems SET `Item ID`= ?, `Name`= ?, `Cost`= ?, `Description`= ? WHERE `Item ID` = ?") or throw_exception("prepare");
		$stmt->bind_param("isisi", $itemid, $itemname, $itemcost, $description, $item_number) or throw_exception("binding");
		$stmt->execute() or throw_exception("execute");
		$created = $itemid;
            } catch(Exception $e) {
		$errno = ($stmt ? $stmt->errno : $db->errno);
		$error = ($stmt ? $stmt->error : $db->error);
		$this->status = "new_item ".$e->getMessage()." failed: (".$errno.") ".$error;
            }
            $stmt and $stmt->close();

            return $created;
        }
        
        public function get_item_info($item_number, $db) {
           $sqlQuery = "SELECT * FROM `MenuItems` WHERE `Item ID` =" . $item_number;
           $result = mysqli_query($db, $sqlQuery);            
           return $result;
        }        
        
        final public function status() {
            return $this->status;
	}
    }
?>
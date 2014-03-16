<?php
    
    class modifyusers {      
        
        public function get_all_users($db) {
           $sqlQuery = "SELECT * FROM `Users` ORDER BY `username`";               
                                 
           $result = mysqli_query($db, $sqlQuery);            
           return $result;
        }
        
        public function display_all_users($all_tables, $db) {                   
        
		
		}
    }
?>

   
<?php
    
    class modifytables {      
        
        public function get_all_tables($db) {
           $sqlQuery = "SELECT * FROM Tables";                        
           $result = mysqli_query($db, $sqlQuery);            
           return $result;
        }
        
        public function display_all_tables($all_tables) {                   
            $table = $table . "<table>";               
           
            $table = $table 
                  . "<thead>"
                  . "<tr>"
                    . "<th bgcolor=\"silver\">Table Number </th>"
                    . "<th bgcolor=\"silver\"> Maximum Size </th>"
                    . "<th bgcolor=\"silver\"> Current Size </th>"
                    . "<th bgcolor=\"silver\"> Size </th>"
                    . "<th bgcolor=\"silver\">     </th>"
                    . "<th bgcolor=\"silver\">     </th>"
                 . "</tr>"
                 . "</thead>";             
          
          $table = $table . "<tbody>";
          while ($row = $all_tables->fetch_array(MYSQLI_NUM)) {               
                $table = $table . "<tbody><tr>";
                for ($number_of_columns = 0;  $number_of_columns < 4;  $number_of_columns++) {
                    $table = $table . "<td>$row[$number_of_columns]</td>";                   
                } 
                $table = $table . "<td><form id= \"name\" method=\"post\" action=\"cleartable.php\">
                                   <input name=\"intable\" type=\"hidden\" value=\"$row[0]\">                                   
                                   <input name=\"submit\" type=\"submit\" value=\"Clear\">
                                   </form></td>";               
                
                $table = $table . "<td><form id= \"name\" method=\"post\" action=\"edittable.php\">
                                   <input name=\"intable\" type=\"hidden\" value=\"$row[0]\">                                   
                                   <input name=\"submit\" type=\"submit\" value=\"Edit Table\">
                                   </form></td>";
                $table = $table . "</tr>";               
           }          
           $table = $table . "</tbody></table>";                 
            
           return $table;
        }      
    }
?>
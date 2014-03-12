<?php
    
    class modifytables {      
        
         
        public function clear_table($db, $table_number) {
          
            $size = '0';
            $status = 'vacant';          
            
            try {
                $change = $db->prepare("UPDATE payomca_rms.Tables SET currentsize = ?, status = ? WHERE Tables.tablenumber = ?") or throw_exception("prepare");
                $change->bind_param("isi", $size, $status, $table_number) or throw_exception("binding");
                $change->execute() or throw_exception("execute");

            } catch(Exception $e) {
                $this->status = "clear ".$e->getMessage()." failed: ";
            }
            
            $change and $change->close();          
        }
        
        
        public function get_all_tables($db, $ordering) {
           if ($ordering == "default") {
               $sqlQuery = "SELECT * FROM Tables";
           } else {              
               $sqlQuery = "SELECT * FROM Tables ORDER BY " . $ordering;               
           }             
                                 
           $result = mysqli_query($db, $sqlQuery);            
           return $result;
        }
        
        public function display_all_tables($all_tables, $db) {                   
            $table = $table . "<table>";               
           
            $table = $table 
                  . "<thead>"
                  . "<tr>"
                    . "<th bgcolor=\"silver\">Table Number</th>"
                    . "<th bgcolor=\"silver\"> Maximum Size </th>"
                    . "<th bgcolor=\"silver\"> Current Size </th>"
                    . "<th bgcolor=\"silver\"> Status </th>"
                    . "<th bgcolor=\"silver\">     </th>"
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
                
                $sqlQuery = "SELECT * FROM payomca_rms.Order WHERE Order.tableid =".$row[0]." AND Order.situation != 'done'";
                $result = mysqli_query($db, $sqlQuery);
                $count = mysqli_num_rows($result);
  
                              
                if ($count==0){
                    $table = $table . "<td><form id= \"name\" method=\"post\" action=\"cleartable.php\">
                                       <input name=\"intable\" type=\"hidden\" value=\"$row[0]\">                                   
                                       <input name=\"submit\" type=\"submit\" value=\"Clear\">
                                       </form></td>";
                } else{
                    $table = $table . "<td><form id= \"name\" method=\"post\>
                                       <input name=\"intable\" type=\"hidden\" value=\"$row[0]\">                                   
                                       <input name=\"submit\" disabled type=\"submit\" value=\"Orders Active\">
                                       </form></td>";
                }
                
                mysqli_free_result($result);
                
                $table = $table . "<td><form id= \"name\" method=\"post\" action=\"edittable.php\">
                                   <input name=\"intable\" type=\"hidden\" value=\"$row[0]\">                                   
                                   <input name=\"submit\" type=\"submit\" value=\"Edit Table\">
                                   </form></td>";
                
                 $table = $table . "<td><form id= \"name\" method=\"post\" action=\"vieworders.php\">
                                   <input name=\"intable\" type=\"hidden\" value=\"$row[0]\">
                                   <input name=\"submit\" type=\"submit\" value=\"View Orders\">
                                   </form></td>";
                 
                $table = $table . "</tr>";               
           }
           mysqli_close($db);

           $table = $table . "</tbody></table>";                 
            
           return $table;
        }      
    }
?>

   
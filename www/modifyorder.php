<?php

    class modifyorder {

        public function get_specific_orders($db, $table_number) {          
           $sqlQuery = "SELECT * FROM `Order` WHERE `tableid` = " . $table_number;           
           $result = mysqli_query($db, $sqlQuery);
           return $result;
        }
        
        public function get_all_orders($db) {
           $sqlQuery = "SELECT * FROM `Order` WHERE 1 ORDER BY tableid ASC";
           $result = mysqli_query($db, $sqlQuery);
           return $result;
        }

        public function display_all_orders($all_orders) {

            $returnstring = $returnstring . "<table>";

            $returnstring = $returnstring
                  . "<thead>"
                  . "<tr>"
                    . "<th bgcolor=\"silver\"> Order ID </th>"
                    . "<th bgcolor=\"silver\"> Table ID </th>"
                    . "<th bgcolor=\"silver\"> Status </th>"
                    . "<th bgcolor=\"silver\">  </th>"
                 . "</tr>"
                 . "</thead>";

          $returnstring = $returnstring . "<tbody>";


          while ($row = $all_orders->fetch_array(MYSQLI_NUM)) {
                $returnstring = $returnstring . "<tbody><tr>";
                for ($number_of_columns = 0;  $number_of_columns < 3;  $number_of_columns++) {
                    $returnstring = $returnstring . "<td>$row[$number_of_columns]</td>";
                }
                $returnstring = $returnstring . "<td><form id= \"name\" method=\"get\" action=\"viewdetails.php\">
                                   <input name=\"orderid\" type=\"hidden\" value=\"$row[0]\">
                                   <input name=\"submit\" type=\"submit\" value=\"View Details\">
                                   </form></td>";
                echo $orderid;

                $returnstring = $returnstring . "</tr>";
           }
           $returnstring = $returnstring . "</tbody></table>";


           return $returnstring;
        }

    }
?>

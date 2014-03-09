<?php

    class modifyorder {

       /* public function get_all_orders($db) {
           $sqlQuery = "SELECT * FROM OrderContain ORDER BY orderid ASC";
           $result = mysqli_query($db, $sqlQuery);
           return $result;
        }*/

        /*public function get_order_id($result) {
           $sqlQuery = "SELECT * FROM OrderContain ORDER BY orderid ASC";
           $result = mysqli_query($db, $sqlQuery);
           return $result;
        }*/

        public function get_table_orders($db) {
           $sqlQuery = "SELECT * FROM Order";
           $result = mysqli_query($db, $sqlQuery);
           return $result;
        }

        public function display_all_orders($all_orders) {
            $order = $order . "<table>";

            $order = $order
                  . "<thead>"
                  . "<tr>"
                    . "<th bgcolor=\"silver\"> Order ID </th>"
                    . "<th bgcolor=\"silver\"> Table ID </th>"
                    . "<th bgcolor=\"silver\"> Status </th>"
                    . "<th bgcolor=\"silver\">     </th>"
                 . "</tr>"
                 . "</thead>";

          $order = $order . "<tbody>";

          while ($row = $all_orders->fetch_array(MYSQLI_NUM)) {
                $order = $order . "<tbody><tr>";
                for ($number_of_columns = 0;  $number_of_columns < 3;  $number_of_columns++) {
                    $order = $order . "<td>$row[$number_of_columns]</td>";
                }
                $order = $order . "<td><form id= \"name\" method=\"post\" action=\"viewfullorder.php\">
                                   <input name=\"intable\" type=\"hidden\" value=\"$row[0]\">
                                   <input name=\"submit\" type=\"submit\" value=\"Clear\">
                                   </form></td>";

                $order = $order . "</tr>";
           }
           $order = $order . "</tbody></table>";

           return $order;
        }

        /*public function display_all_orders($all_orders) {
            $order = $order . "<table>";

            $order = $order
                  . "<thead>"
                  . "<tr>"
                    . "<th bgcolor=\"silver\"> Order ID </th>"
                    . "<th bgcolor=\"silver\"> Item ID </th>"
                    . "<th bgcolor=\"silver\"> Quantity </th>"
                    . "<th bgcolor=\"silver\"> Comments </th>"
                    . "<th bgcolor=\"silver\">     </th>"

                 . "</tr>"
                 . "</thead>";

          $order = $order . "<tbody>";
          while ($row = $all_orders->fetch_array(MYSQLI_NUM)) {
                $order = $order . "<tbody><tr>";
                for ($number_of_columns = 1;  $number_of_columns < 5;  $number_of_columns++) {
                    $order = $order . "<td>$row[$number_of_columns]</td>";
                }
                $order = $order . "<td><form id= \"name\" method=\"post\" action=\"addtoorder.php\">
                                   <input name=\"intable\" type=\"hidden\" value=\"$row[0]\">
                                   <input name=\"submit\" type=\"submit\" value=\"Add to Order\">
                                   </form></td>";

                $order = $order . "</tr>";
           }
           $order = $order . "</tbody></table>";

           return $order;
        }*/
    }
?>

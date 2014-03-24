<?php

    class modifydetails {

        public function get_order_details($db) {
           $sqlQuery = "SELECT * FROM `OrderContain` WHERE 1";
           $result = mysqli_query($db, $sqlQuery);
           return $result;
        }

        public function display_order_details($db, $details, $orderid) {

          $order = " For Order ID: ". $orderid . "<br/>";

          $order = $order . "<table>";

            $order = $order
                  ."<br/"
                  . "<thead>"
                  . "<tr>"
                    . "<th bgcolor=\"silver\"> Item Name </th>"
                    . "<th bgcolor=\"silver\"> Quantity </th>"
                    . "<th bgcolor=\"silver\"> Comment </th>"
                 . "</tr>"
                 . "</thead>";

          $order = $order . "<tbody>";

          while ($rows = $details->fetch_array(MYSQLI_NUM)) {
            if($rows[1] == $orderid){
            $order = $order . "<tbody><tr>";

            $sqlQuery = "SELECT name FROM `MenuItems` WHERE `Item ID`='$rows[2]'";
           
            $rows2 = mysqli_query($db, $sqlQuery);
            $rows2 = $rows2->fetch_array(MYSQLI_NUM);

            $order = $order . "<td>$rows2[0]</td>";
                  for ($number_of_columns = 3;  $number_of_columns < 5;  $number_of_columns++) {
                    $order = $order . "<td>$rows[$number_of_columns]</td>";
                  }
                  $order = $order . "</tr>";
                }

                

            }

            $order = $order . "</tbody></table>";

            return $order;

        }
  }

?>

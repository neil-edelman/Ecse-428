<?php

    class modifydetails {

        public function get_order_details($db) {
           $sqlQuery = "SELECT * FROM `OrderContain` WHERE 1";
           $result = mysqli_query($db, $sqlQuery);
           return $result;
        }


        public function display_order_details($details, $orderid) {

          $exist = 0;

          $order = $order . " For Order ID: ". $orderid . "<br/>";

          $order = $order . "<table>";

            $order = $order
                  ."<br/"
                  . "<thead>"
                  . "<tr>"
                    . "<th bgcolor=\"silver\"> Item ID </th>"
                    . "<th bgcolor=\"silver\"> Quantity </th>"
                    . "<th bgcolor=\"silver\"> Comment </th>"
                 . "</tr>"
                 . "</thead>";

          $order = $order . "<tbody>";

          while ($rows = $details->fetch_array(MYSQLI_NUM)) {
              $order = $order . "<tbody><tr>";
                if($rows[1] == $orderid){
                  for ($number_of_columns = 2;  $number_of_columns < 5;  $number_of_columns++) {
                    $order = $order . "<td>$rows[$number_of_columns]</td>";
                    $exist = 1;
                  }
                }

                $order = $order . "</tr>";

            }

            $order = $order . "</tbody></table>";

              if ($exist != 1){
                  $order = "<h3> No orders corresponding to that order ID can be found </h3>";
                }

            return $order;

        }
  }

?>

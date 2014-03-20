<?php

class Revenues {

    public function get_daily_revenues($db) {
        // Generate table
        $table = $table . "<table>";

        $table = $table
                . "<thead>"
                . "<tr>"
                . "<th bgcolor=\"silver\">Date</th>"
                . "<th bgcolor=\"silver\">Revenue</th>"
                . "</tr>"
                . "</thead>";

        $table = $table . "<tbody>";

        // Get earliest date
        $sqlQuery = "SELECT Min(date) FROM Bills";
        $result = mysqli_query($db, $sqlQuery);
        $result_date = mysqli_fetch_array($result);
        $earliest_date = date_create($result_date[0]);
        date_format($earliest_date, "Y-m-d") . '<br>';

        // Assume last date is current date
        $latest_date = date_create(date("Y-m-d"));
        date_format($latest_date, "Y-m-d") . '<br>';

        // iterate through all dates
        $curr_date = $latest_date;
        while ($curr_date >= $earliest_date) {
            $curr_date_str = date_format($curr_date, "Y-m-d") . '<br>';
            $table = $table . "<td>$curr_date_str</td>";

            $sqlQuery = "SELECT SUM(revenue) FROM Bills WHERE date='" . $curr_date_str . "'";
            $result = mysqli_query($db, $sqlQuery);
            $daily_revenue = mysqli_fetch_array($result, MYSQLI_BOTH);
            
            if ($daily_revenue[0] === NULL) {
                $table = $table . "<td>0.00</td>";
            } else {
                $table = $table . "<td>$daily_revenue[0]</td>";
            }

            mysqli_free_result($result);

            $table = $table . "</tr>";

            date_sub($curr_date, new DateInterval('P1D'));
        }

        mysqli_close($db);

        $table = $table . "</tbody></table>";

        echo $table;
    }

}

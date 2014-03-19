<?php

/**
 * Description of modifyitems
 *
 * @author jonathan
 */
class modifyitems {

    private $status = "okay";

    final public function new_item($itemid, $itemname, $itemcost, $description, $db) {
        $created = null;
        try {
            $stmt = $db->prepare("INSERT INTO `MenuItems` (`Item ID`, `Name`, `Cost`, `Description`) "
                    . "VALUES (?, ?, ?, ?)") or throw_exception("prepare");
            $stmt->bind_param("isis", $itemid, $itemname, $itemcost, $description) or throw_exception("binding");
            $stmt->execute() or throw_exception("execute");
            $created = $itemname;
        } catch (Exception $e) {
            $errno = ($stmt ? $stmt->errno : $db->errno);
            $error = ($stmt ? $stmt->error : $db->error);
            $this->status = "new_item " . $e->getMessage() . " failed: (" . $errno . ") " . $error;
        }
        $stmt and $stmt->close();

        return $created;
    }

    final public function edit_item($item_number, $itemid, $itemname, $itemcost, $description, $db) {
        $created = null;
        try {
            $stmt = $db->prepare("UPDATE MenuItems SET `Item ID`= ?, `Name`= ?, `Cost`= ?, `Description`= ? WHERE `Item ID` = ?") or throw_exception("prepare");
            $stmt->bind_param("isisi", $itemid, $itemname, $itemcost, $description, $item_number) or throw_exception("binding");
            $stmt->execute() or throw_exception("execute");
            $created = $itemid;
        } catch (Exception $e) {
            $errno = ($stmt ? $stmt->errno : $db->errno);
            $error = ($stmt ? $stmt->error : $db->error);
            $this->status = "new_item " . $e->getMessage() . " failed: (" . $errno . ") " . $error;
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

    public function get_all_items($db, $ordering) {
        if ($ordering == "default") {
            $sqlQuery = "SELECT * FROM payomca_rms.MenuItems";
        } else {
            $sqlQuery = "SELECT * FROM MenuItems ORDER BY " . $ordering;
        }

        $result = mysqli_query($db, $sqlQuery);
        return $result;
    }

    public function display_all_items($all_items, $db) {
        $table = $table . "<table>";

        $table = $table
                . "<thead>"
                . "<tr>"
                . "<th bgcolor=\"silver\"> Item ID</th>"
                . "<th bgcolor=\"silver\"> Name </th>"
                . "<th bgcolor=\"silver\"> Price </th>"
                . "<th bgcolor=\"silver\"> Description </th>"
                . "<th bgcolor=\"silver\">     </th>"
                . "</tr>"
                . "</thead>";

        $table = $table . "<tbody>";
        while ($row = $all_items->fetch_array(MYSQLI_NUM)) {
            $table = $table . "<tbody><tr>";
            for ($number_of_columns = 0; $number_of_columns < 4; $number_of_columns++) {
                $table = $table . "<td>$row[$number_of_columns]</td>";
            }

            $table = $table . "<td><form id= \"name\" method=\"post\" action=\"edititem.php\">
                                   <input name=\"intable\" type=\"hidden\" value=\"$row[0]\">                                   
                                   <input name=\"submit\" type=\"submit\" value=\"Edit Table\">
                                   </form></td>";

            $table = $table . "</tr>";
        }

        $table = $table . "</tbody></table>";

        return $table;
    }

    public function get_popular_items($db, $ordering) {


        $sqlQuery = "SELECT DISTINCT `MenuItems`.`Item ID`, `MenuItems`.`Name`, `MenuItems`.`Cost`, (SELECT SUM(`quantity`) AS popcount FROM OrderContain WHERE `OrderContain`.`itemid` = `MenuItems`.`Item ID` GROUP BY `itemid`) AS popularity FROM MenuItems, OrderContain ORDER BY" . $ordering;

        $result = mysqli_query($db, $sqlQuery);

        return $result;
    }

    public function display_popular_items($all_items) {
        $table = $table . "<table>";

        $table = $table
                . "<thead>"
                . "<tr>"
                . "<th bgcolor=\"silver\"> Item ID</th>"
                . "<th bgcolor=\"silver\"> Name </th>"
                . "<th bgcolor=\"silver\"> Price </th>"
                . "<th bgcolor=\"silver\"> Quantity </th>"
                . "<th bgcolor=\"silver\"> Revenues </th>"
                . "</tr>"
                . "</thead>";

        $table = $table . "<tbody>";
        while ($row = $all_items->fetch_array(MYSQLI_NUM)) {
            $table = $table . "<tbody><tr>";
            for ($number_of_columns = 0; $number_of_columns < 4; $number_of_columns++) {
                if ($row[$number_of_columns] == NULL) {
                    $table = $table . "<td>0</td>";
                } else {
                    $table = $table . "<td>$row[$number_of_columns]</td>";
                }
            }
            $revenues = $row[2] * $row[3];
            $table = $table . "<td>$revenues</td>";

            $table = $table . "</tr>";
        }

        $table = $table . "</tbody></table>";

        return $table;
    }

}

?>
<?php
namespace Parser\Database;

class Functions { 

    public static function GetProducts($link, $table) {

        $sql = "SELECT * FROM `$table`";

        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);

        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $products;
    }

    public static function SetProducts($link, $table, $tproduct_id, $tproduct_title, $tproduct_link, $tproduct_price, $tproduct_prime) {

        $sql = "INSERT INTO `$table`(`tproduct_id`, `tproduct_title`, `tproduct_link`, `tproduct_price`, `tproduct_prime`) VALUES ('$tproduct_id','$tproduct_title','$tproduct_link','$tproduct_price','$tproduct_prime')";
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);

        if($result == FALSE) {
            echo "" . mysqli_error($link) ." Error number: " . mysqli_errno($link) ."<br/>";

            if(mysqli_errno($link) == 1062) {
                $descr = Functions::GetReleases($link);
                return 1062;
            }
        }
    }

    /*public static function GetReleases($link) {

        $sql = "SELECT * FROM `releases` ORDER BY `releases`.`id` ASC, `releases`.`date` DESC";

        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);

        $releases = mysqli_fetch_all($result, MYSQLI_ASSOC);

        return $releases;
    }

    public static function SetReleases($link, $release = NULL, $amount = NULL, $current = NULL, $rlink = NULL, $tlink = NULL, $id = NULL, $date = NULL) {

        $sql = "INSERT INTO `releases`(`release`, `amount`, `current`, `rlink`, `tlink`, `id`, `date`) VALUES ('$release','$amount','$current','$rlink','$tlink','$id','$date')";
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);

        if($result == FALSE) {
            echo "" . mysqli_error($link) ." Error number: " . mysqli_errno($link) ."<br/>";

            if(mysqli_errno($link) == 1062) {
                $descr = Functions::GetReleases($link);
                for($i = 0; $i < count($descr); $i++) {
                    if($descr[$i]['release'] == $release) {
                        if($descr[$i]['amount'] != $amount && $descr[$i]['current'] == $current) {
                            $sql = "UPDATE `releases` SET `amount` = '$amount', `date` = '$date', `id` = '$id', `tlink` = '$tlink' WHERE `release` = '$release'";
                        } elseif($descr[$i]['amount'] == $amount && $descr[$i]['current'] != $current) {
                            $sql = "UPDATE `releases` SET `current` = '$current', `date` = '$date', `id` = '$id', `tlink` = '$tlink' WHERE `release` = '$release'";
                        } elseif($descr[$i]['amount'] != $amount && $descr[$i]['current'] != $current) {
                            $sql = "UPDATE `releases` SET `amount` = '$amount', `current` = '$current', `date` = '$date', `id` = '$id', `tlink` = '$tlink' WHERE `release` = '$release'";
                        } else {
                            return 1062;
                        }
                    }
                }
                $result = mysqli_query($link, "SET NAMES utf8");
                $result = mysqli_query($link, $sql); 
            }
        }
    }

    public static function SetIds($link) {

        $sql = NULL;
        $num = 0;
        $descr = Functions::GetReleases($link);
        $newrel = array();
        for($i = 0; $i < count($descr); $i++) {
            if($descr[$i]['id'] == $descr[$i+1]['id']) {
                $num++;
                array_push($newrel, strval($descr[$i]['release']));
            }
            if($i == (count($descr) - 2)) {
                break;
            }
        }
        for($i = 0, $iftrue = 0; $i < count($descr); $i++) {
            for($j = 0; $j < count($newrel); $j++) {
                if($descr[$i]['release'] != $newrel[$j]) {
                    $iftrue++;
                }
            }
            if($iftrue == $num) {
                $sql = "UPDATE `releases` SET `id` = " . intval($descr[$i]['id'] + $num) . " WHERE `release` = '" . $descr[$i]['release'] . "'";
                $result = mysqli_query($link, "SET NAMES utf8");
                $result = mysqli_query($link, $sql);
            }
            $iftrue = 0;
        }
    }*/
}
?>
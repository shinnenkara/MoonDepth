<?php
namespace Messenger\Database;

class FunctionsMessenger {

    public static function GetMessages($link, $user_id, $username = NULL) {

        $sql = "SELECT * FROM `$user_id` ORDER BY `message_index` DESC";
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);
        $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $messages;
    }

    public static function GetUsers($link, $user_id = NULL, $username = NULL) {

        $sql = "SELECT * FROM `usersid`";
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);

        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $users;
    }

    public static function AddUser($link, $user_id, $username) {

        $db_name = $user_id;
        $sql = "CREATE TABLE `userdb`.`$db_name` ( `message_index` INT NOT NULL ," . 
        " `message_text` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ," . 
        " `message_from_id` INT NOT NULL ," . 
        " `message_from_username` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ," . 
        " `message_to_id` INT NOT NULL ," . 
        " `message_to_username` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ," . 
        " PRIMARY KEY (`message_index`)) ENGINE = InnoDB";
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);
        if($result == TRUE) {
            $sql_m = "INSERT INTO `$user_id`(`message_index`, `message_text`, `message_from_id`, `message_from_username`, `message_to_id`, `message_to_username`) VALUES ('0','start','$user_id','$username','$user_id','$username')";
            $result_m = mysqli_query($link, "SET NAMES utf8");
            $result_m = mysqli_query($link, $sql_m);
            //echo "" . mysqli_error($link) ." Error number: " . mysqli_errno($link) ."<br/>";

            $sql_u = "INSERT INTO `usersid` (`userId`, `username`) VALUES ('$user_id', '$username')";
            $result_u = mysqli_query($link, "SET NAMES utf8");
            $result_u = mysqli_query($link, $sql_u);
            //echo "" . mysqli_error($link) ." Error number: " . mysqli_errno($link) ."<br/>";
        } elseif($result == FALSE) {
            echo "" . mysqli_error($link) ." Error number: " . mysqli_errno($link) ."<br/>";
            if(mysqli_errno($link) == 1050) {
                
            }
        }
    }

    public static function AddMessage($link, $message_text, $message_from_id, $message_from_username, $message_to_id, $message_to_username) {

        $messages = FunctionsMessenger::GetMessages($link, $message_from_id);
        $sql = "INSERT INTO `$message_from_id`(`message_index`, `message_text`, `message_from_id`, `message_from_username`, `message_to_id`, `message_to_username`) VALUES (" . "'" . (intval($messages[0]['message_index']) + 1) . "'" . ",'$message_text','$message_from_id','$message_from_username','$message_to_id','$message_to_username')";
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);

        if($result == FALSE) {
            echo "" . mysqli_error($link) ." Error number: " . mysqli_errno($link) ."<br/>";
        }

        return $result;
    }
}
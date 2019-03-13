<?php
namespace WebServer\php;

class Database {

    //link;
    //$stmt = mysqli_stmt_init($link);

    public static function GetLinkToDB() {

        #$url = "";
        $server = 'localhost';
        $username = 'root';
        $password = '';
        $db = 'moondepth_local';
        $link = mysqli_connect($server, $username, $password, $db);
        if(mysqli_connect_errno()) {
            echo "Error (" . mysqli_connect_errno() . "): " . mysqli_connect_error();
            exit();
        }
        return $link;
    }

    public static function GetBoards($link) {

        $sql = "SELECT * FROM `boards` ORDER BY `boards`.`headline` ASC";
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);
        if($result == FALSE) {
            echo "" . mysqli_error($link) . " Error number: " . mysqli_errno($link) . "<br/>";
        }
        $boards = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $boards;
    }

    public static function FindBigBoard($boards) {

        $big_board = $boards[0];
        for($i = 0; $i < count($boards); $i++) {
            if($big_board["amount_of_threads"] < $boards[$i]["amount_of_threads"]) {
                $big_board = $boards[$i];
            }
        }
        return $big_board;
    }

    public static function GetThreads($link, $board_headline = NULL, $thread_id = NULL) {

        if(isset($thread_id)) {
            $sql = "SELECT * FROM `threads` WHERE `id` = \"$thread_id\" ORDER BY `threads`.`last_message_date` DESC";
        } elseif(isset($board_headline)) {
            $sql = "SELECT * FROM `threads` WHERE `bid` = \"$board_headline\" ORDER BY `threads`.`last_message_date` DESC";
        } else {
            $sql = "SELECT * FROM `threads` ORDER BY `id` ASC";
        }
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);
        if($result == FALSE) {
            echo "" . mysqli_error($link) . " Error number: " . mysqli_errno($link) . "<br/>";
        }
        $threads = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $threads;
    }

    public static function AddThread($link, $bid, $uid, $topic, $subject_text, $creation_date, $amount_of_messages = 1, $amount_of_documents = 0, $last_message_date = NULL) {    

        $last_message_date = $creation_date;
        $threads = Database::GetThreads($link);
        $threads_amount = count($threads);
        $newThreads_id = ++$threads_amount;
        $sql = "INSERT INTO `threads` (`id`, `bid`, `uid`, `topic`, `subject_text`, `amount_of_messages`, `amount_of_documents`, `creation_date`, `last_message_date`) " .
            "VALUES ($newThreads_id, '$bid', '$uid', '$topic', '$subject_text', '$amount_of_messages', '$amount_of_documents', '$creation_date', '$last_message_date')";
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);
        if($result == FALSE) {
            echo "" . mysqli_error($link) ." Error number: " . mysqli_errno($link) ."<br/>";
            if(mysqli_errno($link) == 1050) {
                
            }
        }
        return $newThreads_id;
    }

    public static function UpdateThread($link, $thread_id, $last_message_date) {

        $sql = "UPDATE `threads` SET `last_message_date` = '$last_message_date' WHERE `id` = $thread_id;";
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql); 
        if($result == FALSE) {

        }
    }

    public static function GetUsers($link, $user_id = NULL, $user_name = NULL) {
        
        if(isset($user_name)) {
            $sql = "SELECT * FROM `users` WHERE `username` = \"$user_name\"";
        } elseif(isset($user_id)) {
            $sql = "SELECT * FROM `users` WHERE `id` = \"$user_id\"";
        } else {
            $sql = "SELECT * FROM `users` ORDER BY `id` ASC";
        }
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);
        if($result == FALSE) {
            echo "" . mysqli_error($link) . " Error number: " . mysqli_errno($link) . "<br/>";
        }
        $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $users;
    }

    public static function AddUser($link, $username = "Anonymous") {    

        if($username != "Anonymous") {
            $user = Database::GetUsers($link, 0, $username);
            if(!empty($user)) {
                $user_id = $user[0]["id"];
                return $user_id;
            }
        }
        $users = Database::GetUsers($link);
        $users_amount = count($users);
        $newUser_id = ++$users_amount;
        $sql = "INSERT INTO `users` (`id`, `username`, `ip`) VALUES ('$newUser_id', '$username', NULL)";
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);
        if($result == FALSE) {
            echo "" . mysqli_error($link) ." Error number: " . mysqli_errno($link) ."<br/>";
            if(mysqli_errno($link) == 1050) {
                
            }
        }
        return $newUser_id;
    }

    public static function GetMessages($link, $thread_id = NULL, $message_id = NULL) {

        if(isset($thread_id)) {
            $sql = "SELECT * FROM `messages` WHERE `tid` = \"$thread_id\" ORDER BY `messages`.`message_date` ASC";
        } else {
            $sql = "SELECT * FROM `messages` ORDER BY `id` ASC";
        }
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);
        if($result == FALSE) {
            echo "" . mysqli_error($link) ." Error number: " . mysqli_errno($link) ."<br/>";
        }
        $messages = mysqli_fetch_all($result, MYSQLI_ASSOC);
        return $messages;
    }

    public static function AddMessage($link, $uid, $tid, $text, $message_date, $response_to = NULL) {

        $messages = Database::GetMessages($link);
        $messages_amount = count($messages);
        $newMessages_id = ++$messages_amount;
        if(isset($response_to)) {
            $sql = "INSERT INTO `messages` (`id`, `uid`, `tid`, `response_to`, `text`, `message_date`) " . 
                "VALUES ($newMessages_id, '$uid', '$tid', '$response_to', '$text', '$message_date')";
        } else {
            $sql = "INSERT INTO `messages` (`id`, `uid`, `tid`, `text`, `message_date`) " . 
                "VALUES ($newMessages_id, '$uid', '$tid', '$text', '$message_date')";
        }
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql);
        if($result == FALSE) {
            echo "" . mysqli_error($link) ." Error number: " . mysqli_errno($link) ."<br/>";
            if(mysqli_errno($link) == 1050) {
                
            }
        }
    }

    public static function UpdateStatus($link, $user_id, $status) {

        $users = FunctionsMessenger::GetUsers($link);
        $sql = "";
        for($i = 0; $i < count($users); $i++) {
            if($users[$i]['userId'] == $user_id) {
                $sql = "UPDATE `usersid` SET `userstatus` = '$status' WHERE `usersid`.`userId` = $user_id;";
            }
        }
        $result = mysqli_query($link, "SET NAMES utf8");
        $result = mysqli_query($link, $sql); 
    }
}
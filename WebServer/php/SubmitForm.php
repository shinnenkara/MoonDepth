<?php
include "Database.php";
ini_set('error_reporting', E_ALL);
use \WebServer\php\Database;
$link = Database::GetLinkToDB();

if(isset($_POST["submit_thread"])) {
    $headline = $_POST["headline"];
    $newThread_topic = mysqli_real_escape_string($link, $_POST["topic"]);
    $newThread_subject_text = mysqli_real_escape_string($link, $_POST["subject_text"]);
    if(empty($newThread_topic) && !empty($newThread_subject_text)) {
        echo "Fill in topic field!";
    } elseif(!empty($newThread_topic) && empty($newThread_subject_text)) {
        echo "Fill in field with subject text!";
    } elseif(empty($newThread_topic) && empty($newThread_subject_text)) {
        echo "Fill in all fields!";
    } else {
        $newThread_username = mysqli_real_escape_string($link, $_POST["username"]);
        if(!preg_match('/^[a-zA-Z0-9]{4,}$/', $newThread_username)) {
            echo "Username must contain only alphanumeric characters<br/>And more than 4 characters long";
        } else {
            if($_POST["username"] != "") {
                $newUser_id = Database::AddUser($link, $newThread_username);
            } else {
                $newUser_id = Database::AddUser($link);
            }
            $newThreads_id = Database::AddThread($link, $headline, $newUser_id, $newThread_topic, $newThread_subject_text, date("Y-m-d H:i:s"));
            echo "Thread <a class=\"grey-text text-lighten-1\" href=\"thread?id=" . $newThreads_id . "\">$newThread_topic</a> have been successfully created";
        }
    }
    if(isset($_FILES['file_array'])) {
        $file_array = $_FILES['file_array'];
        echo "<br />" . $file_array["name"];
    } else {
        echo "<br />" . "There are no files...";
    }
} elseif(isset($_POST["submit_message"])) {
    $thread_id = $_POST["thread_id"];
    $newMessage_response_to = mysqli_real_escape_string($link, $_POST["response_to"]);
    $newMessage_message_text = mysqli_real_escape_string($link, $_POST["message_text"]);
    if(empty($newMessage_message_text)) {
        echo "Fill in field with message text!";
    } else {
        $newMessage_username = mysqli_real_escape_string($link, $_POST["username"]);
        if(!preg_match('/^[a-zA-Z0-9]{4,}$/', $newMessage_username)) {
            echo "Username must contain only alphanumeric characters<br/>And more than 4 characters long";
        } else {
            if($_POST["username"] != "") {
                $newUser_id = Database::AddUser($link, $newMessage_username);
            } else {
                $newUser_id = Database::AddUser($link);
            }
            $newMessage_id = Database::AddMessage($link, $newUser_id, $thread_id, $newMessage_message_text, date("Y-m-d H:i:s"));
            Database::UpdateThread($link, $thread_id, date("Y-m-d H:i:s"));
            echo "New message have been successfully created";
        }
    }
}
?>
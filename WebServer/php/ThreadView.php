<?php
include "Database.php";
ini_set('error_reporting', E_ALL);
use \WebServer\php\Database;
$link = Database::GetLinkToDB();

echo "<div class=\"row\">";
$thread_id = $_POST["thread_id"];
$messages = Database::GetMessages($link, $thread_id);
foreach($messages as $message) {
    $message_id = $message["id"];
    $response_to = $message["response_to"];
    $message_text = $message["text"];
    $message_date = $message["message_date"];
    $message_date = str_replace("-", ".", strval($message_date));
    $message_date = str_replace(" ", "-", strval($message_date));
    $visitor = Database::GetUsers($link, $message["uid"]);
    $visitor_username = $visitor[0]["username"];
    echo "<div class=\"col message\" style=\"width: 90%; padding: 1.5rem .75rem;\">
        <div class=\"message-head\">
            <i class=\"material-icons hidemessage-icons\">arrow_drop_down</i>
            <h5 class=\"grey-text text-lighten-1\" style=\"display: inline\">" . $visitor_username . "</h5>
            <h5 style=\"display: inline\">" . $message_date . "</h5>
            <a class=\"white-text\" href=\"http://localhost/projects/GitHub/MoonDepth/thread?id=" . 
            $thread_id . "\"><h5 style=\"display: inline\">No. " . $message_id . "</h5></a>
        </div>
        <div class=\"message-body\">
            </br>
            <div class=\"container\">
                <h5 style=\"display: inline\">" . $message_text . "</h5></span>
            </div>
            </br>
        </div>
    </div>";
}
echo "</div>";
?>
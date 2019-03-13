<?php
include "Database.php";
ini_set('error_reporting', E_ALL);
use \WebServer\php\Database;
$link = Database::GetLinkToDB();

echo "<div class=\"row\">";
$headline = $_POST["headline"];
    $threads = Database::GetThreads($link, $headline);
    foreach($threads as $thread) {
        $thread_id = $thread["id"];
        $topic = $thread["topic"];
        $subject_text = $thread["subject_text"];
        $creation_date = $thread["creation_date"];
        $creation_date = str_replace("-", ".", strval($creation_date));
        $creation_date = str_replace(" ", "-", strval($creation_date));
        $creator = Database::GetUsers($link, $thread["uid"]);
        $creator_username = $creator[0]["username"];
        echo "<div class=\"col thread\" style=\"padding: 1.5rem .75rem;\">
            <div class=\"thread-head\">
                <i class=\"material-icons hidethread-icons\">arrow_drop_down</i>
                <h5 style=\"display: inline\"><strong>" . $topic . "</strong></h5>
                <h5 class=\"grey-text text-lighten-1\" style=\"display: inline\">" . $creator_username . "</h5>
                <h5 style=\"display: inline\">" . $creation_date . "</h5>
                <a class=\"white-text\" href=\"thread?id=" . $thread_id . "\"><h5 style=\"display: inline\">No. " . $thread_id . "</h5></a>
                <a class=\"white-text\" href=\"thread?id=" . $thread_id . "\"><h5 style=\"display: inline\">[<span class=\"grey-text text-lighten-1\">Reply</span>]</h5></a>
            </div>
            <div class=\"thread-body\">
                </br>
                <div class=\"container\">
                    <h5 style=\"display: inline\">" . $subject_text . "</h5></span>
                </div>
                </br>
            </div>";
            echo "<div class=\"container white-text messages\">
                <div class=\"row\">";
                    $messages = Database::GetMessages($link, $thread_id);
                    $messages_amount = count($messages);
                    if($messages_amount < 3) {
                        $first_message = 0;
                    } else {
                        $first_message = $messages_amount - 3;
                    }
                    for($i = $first_message; $i < $messages_amount; $i++) {
                        $message = $messages[$i];
                        $message_id = $message["id"];
                        $response_to = $message["response_to"];
                        $message_text = $message["text"];
                        $message_date = $message["message_date"];
                        $message_date = str_replace("-", ".", strval($message_date));
                        $message_date = str_replace(" ", "-", strval($message_date));
                        $visitor = Database::GetUsers($link, $message["uid"]);
                        $visitor_username = $creator[0]["username"];
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
                echo "</div>
            </div>
        </div>";
    }
echo "</div>";
?>
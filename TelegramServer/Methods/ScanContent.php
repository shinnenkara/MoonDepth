<?php
namespace TelegramServer\Methods;

class ScanContent {

    public static function Message($text = NULL) {

        $message = array();
        $message = explode("/", $text);
        return $message;

    }

    public static function MessageType($message = NULL, $message_type = NULL) {

        switch($message_type) {
            case NULL: {
                if(count($message) >= 3) {
                    $message_type = "badmessage";
                } elseif($message[0] == NULL) {
                    $message_type = "command";
                } else {
                    $message_type = "message";
                }
                break;
            }
            case "message": {
                /*$message_type = "question";

                $message_type = "rage";

                $message_type = "";

                ...*/
                break;
            }
            default: {
                $message_type = "message";
            }
        }
        return $message_type;
    }

    public static function CommandType($command = NULL, $command_type = NULL) { 
        
        switch($command_type) {
            case NULL: {
                $t = explode("_", $command);
                if($t[0] >= 0) {
                    $command_type = "integer";
                } else {
                    $command_type = "text";
                }
                break;
            }
            default: {
                $command_type = "badcommand";
            }
        }
        return $command_type;
    }
}
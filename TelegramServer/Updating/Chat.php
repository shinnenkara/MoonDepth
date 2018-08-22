<?php
namespace TelegramServer\Updating;

class Chat {

    public static function WebUpdate() {

        $update = file_get_contents("php://input");
        return json_decode($update, TRUE);
    }

    public static function GetUpdate($website = NULL) {
        
        $update = file_get_contents($website . "/getUpdates");
        return json_decode($update, TRUE);
    }    

    public static function GetWebChatId($updatedArray = NULL) {

        return $updatedArray["message"]["chat"]["id"];
    }

    public static function GetUpdChatId($updatedArray = NULL) {

        return $updatedArray["result"][0]["message"]["chat"]["id"];
    }    

    public static function GetWebText($updatedArray = NULL) {

        return $updatedArray["message"]["text"];
    }

    public static function GetUpdText($updatedArray = NULL) {

        return $updatedArray["result"][count($updatedArray["result"])-1]["message"]["text"];
    }


}
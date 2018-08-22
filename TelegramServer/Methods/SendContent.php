<?php
namespace TelegramServer\Methods;

class SendContent {

    public static function SendMessage($sendmessage_options = NULL) {

        $url = $sendmessage_options['website'] . "/sendMessage?chat_id=" . $sendmessage_options['chatId'] . "&text=" . urlencode($sendmessage_options['response']) . "";

        if($sendmessage_options['keyboard'] != NULL) {
            $url .= "&reply_markup=" . json_encode($sendmessage_options['keyboard']) . "";
        }
        
        file_get_contents($url);
    }

}
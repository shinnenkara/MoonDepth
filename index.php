<?php
include 'WebServer/MainPage.html';
ini_set('error_reporting', E_ALL);

include 'Messenger/Database/IncludingMessenger.php';
include 'Messenger/Database/FunctionsMessenger.php';
include 'Messenger/MessengerAPI.php';
use \Messenger\Database\FunctionsMessenger;
use \Messenger\Database\IncludingMessenger;
use \Messenger\MessengerAPI;
include 'Parser/cURL/cURLOptions.php';
include 'Parser/Database/IncludingParser.php';
include 'Parser/Database/FunctionsParser.php';
include 'Parser/Exceptions/cURLExceptions.php';
include 'Parser/ParserAPI.php';
include 'Parser/simple_html_dom.php';
use \Parser\Database\FunctionsParser;
use \Parser\Database\IncludingParser;
use \Parser\ParserAPI;
include_once 'TelegramServer/Methods/SendContent.php';
include_once 'TelegramServer/Methods/ScanContent.php';
include_once 'TelegramServer/Methods/SetOptionsFrom.php';
include_once 'TelegramServer/Updating/Chat.php';
use \TelegramServer\Methods\ScanContent;
use \TelegramServer\Methods\SendContent;
use \TelegramServer\Methods\SetOptionsFrom;
use \TelegramServer\Updating\Chat;

$Token = "622213887:AAE0JQsOmBj6NVNf3eWhX2OFVQWyIoxITfM";
$website = "https://api.telegram.org/bot" . $Token;
$parsed_link = IncludingParser::GetLink();
$messenger_link = IncludingMessenger::GetLink();
$products = array();
//ParserAPI::ParseNew($parsed_link);
/*$parsed_link = IncludingParser::GetLink();
$products = Functions::GetProducts($parsed_link);
$current_date = intval(date("ymdHi"));
if($current_date > (intval($products[0]['date']) + 100)) {
    ParserAPI::ParseNew($parsed_link);
    $products = Functions::GetProducts($parsed_link);
}*/

/* GetUpdates or Webhook methods */
#$updatedArray = Chat::WebUpdate();
#$chatId = Chat::GetWebChatId($updatedArray);
#$text = Chat::GetWebText($updatedArray);
#$username = Chat::GetWebChatUsername($updatedArray);

$updatedArray = Chat::GetUpdate($website);
$chatId = Chat::GetUpdChatId($updatedArray);
$text = Chat::GetUpdText($updatedArray);
$username = Chat::GetUpdChatUsername($updatedArray);

$sendmessage_options = array('website' => $website, 'chatId' => $chatId, 'username' => $username, 'response' => NULL, 'keyboard' => NULL);
$message = ScanContent::Message($text);
$message_type = ScanContent::MessageType($message);
switch($message_type) {
    case "command": {
        $command = $message[1];
        $command_type = ScanContent::CommandType($command);
        $sendmessage_options = SetOptionsFrom::Command($sendmessage_options, $command, $command_type, $products);
        break;
    }
    case "message": {
        $text_responses = array(0 => "Yes ?", "Here I come", "How ?", "You are nice)", "Nya", "You laught - you lose!", "*ugly barking*");
        $sendmessage_options['response'] = $text_responses[rand(0, 6)];
        break;
    }
    case "badmessage": {
        $sendmessage_options['response'] = "Something goes wrong...";
        break;
    }
    default: {
        $sendmessage_options['response'] = "Try again";
    }
}
SendContent::SendMessage($sendmessage_options);
?>
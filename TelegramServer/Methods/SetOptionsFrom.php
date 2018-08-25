<?php
namespace TelegramServer\Methods;

use Parser\cURL\cURLOptions;
use Parser\Database\FunctionsParser;
use Parser\Database\IncludingParser;
use Messenger\Database\FunctionsMessenger;
use Messenger\Database\IncludingMessenger;

class SetOptionsFrom {

    public static function Command($sendmessage_options = NULL, $command = NULL, $command_type = "badcommand", $products = NULL) {

        $today_date = intval(date("ymd") . "0000");
        $current_weekstart_date = intval(date("ymd") . "0000") - ((intval(date("N")) - 1) * 10000 );

        $parsed_link = IncludingParser::GetLink();
        $messenger_link = IncludingMessenger::GetLink();

        switch($command_type) {
            case "integer": {
                $command = explode("_", $command, 2);
                $products = FunctionsParser::GetProducts($parsed_link, '' . $command[1] . '');
                if($command[0] <= count($products)) {
                    $sendmessage_options['response'] .= "Price: $" . $products[intval($command[0])-1]['tproduct_price'] . "\n";
                    if($products[intval($command[0])-1]['tproduct_prime'] == 1) {
                        $sendmessage_options['response'] .= "Prime account" . "\n";
                    }
                    if($products[intval($command[0])-1]['tproduct_rate'] != NULL) {
                        $sendmessage_options['response'] .= $products[intval($command[0])-1]['tproduct_rate'] . "\n";
                    }
                    $sendmessage_options['response'] .= $products[intval($command[0])-1]['tproduct_link'] . "";
                    /*$sendmessage_options['keyboard'] = [ 
                        'inline_keyboard' => [
                            [
                                ['text' => 'Torrent link', 'url' => $products[intval($command)-1]['tlink']]
                                # 'url' => $products[$message[1]-1]['tlink'], 'callback_data' => 'someString'
                            ]
                        ]
                    ];*/
                } else {
                    $sendmessage_options['response'] = "Wrong number of release...";
                }
                break;
            }
            case "text": {
                switch($command) {
                    case "start": {
                        FunctionsMessenger::AddUser($messenger_link, $sendmessage_options['chatId'], $sendmessage_options['username']);
                        $sendmessage_options['response'] = "Nice to meet you";
                        break;
                    }
                    case "departments": {
                        $sendmessage_options['response'] .= "...%in_development%..." . "";
                        //$sendmessage_options['response'] .= "/game_hardware" . "\n" . "...%in_development%...";
                        break;
                    }
                    case "game_hardware": {
                        $sendmessage_options['response'] .= "/GH_best_sellers"
                                                         . "\n" . "/GH_hot_new_releases"
                                                         . "\n" . "/GH_top_rated"
                                                         . "\n" . "/GH_most_wished_for"
                                                         . "\n" . "/GH_most_gifted"
                                                         . "\n" . "/GH_all"
                                                         . "";
                        break;
                    }
                    case "GH_best_sellers": {
                        $products = FunctionsParser::GetProducts($parsed_link, 'GH_best_sellers');
                        //for($i = 0; $i < count($products); $i++) {
                        for($i = 0; $i < 10; $i++) {
                            //if($products[$i]['date'] >= $today_date) {
                                if($sendmessage_options['response'] == NULL) {
                                    $sendmessage_options['response'] .= "/1" . "_GH_best_sellers " . $products[$i]['tproduct_title'] . "";
                                } else {
                                    $sendmessage_options['response'] .= "\n" . "/" . strval($i+1) . "_GH_best_sellers" . " " . $products[$i]['tproduct_title'] . "";
                                }
                            //}
                        }
                        break;
                    }
                    case "GH_hot_new_releases": {
                        $products = FunctionsParser::GetProducts($parsed_link, 'GH_hot_new_releases');
                        for($i = 0; $i < 10; $i++) {
                            if($sendmessage_options['response'] == NULL) {
                                $sendmessage_options['response'] .= "/1" . "_GH_hot_new_releases " . $products[$i]['tproduct_title'] . "";
                            } else {
                                $sendmessage_options['response'] .= "\n" . "/" . strval($i+1) . "_GH_hot_new_releases" . " " . $products[$i]['tproduct_title'] . "";
                            }
                        }
                        break;
                    }
                    case "GH_top_rated": {
                        $products = FunctionsParser::GetProducts($parsed_link, 'GH_top_rated');
                        for($i = 0; $i < 10; $i++) {
                            if($sendmessage_options['response'] == NULL) {
                                $sendmessage_options['response'] .= "/1" . "_GH_top_rated " . $products[$i]['tproduct_title'] . "";
                            } else {
                                $sendmessage_options['response'] .= "\n" . "/" . strval($i+1) . "_GH_top_rated" . " " . $products[$i]['tproduct_title'] . "";
                            }
                        }
                        break;
                    }
                    case "GH_most_wished_for": {
                        $products = FunctionsParser::GetProducts($parsed_link, 'GH_most_wished_for');
                        for($i = 0; $i < 10; $i++) {
                            if($sendmessage_options['response'] == NULL) {
                                $sendmessage_options['response'] .= "/1" . "_GH_most_wished_for " . $products[$i]['tproduct_title'] . "";
                            } else {
                                $sendmessage_options['response'] .= "\n" . "/" . strval($i+1) . "_GH_most_wished_for" . " " . $products[$i]['tproduct_title'] . "";
                            }
                        }
                        break;
                    }
                    case "GH_most_gifted": {
                        $products = FunctionsParser::GetProducts($parsed_link, 'GH_most_gifted');
                        for($i = 0; $i < 10; $i++) {
                            if($sendmessage_options['response'] == NULL) {
                                $sendmessage_options['response'] .= "/1" . "_GH_most_gifted " . $products[$i]['tproduct_title'] . "";
                            } else {
                                $sendmessage_options['response'] .= "\n" . "/" . strval($i+1) . "_GH_most_gifted" . " " . $products[$i]['tproduct_title'] . "";
                            }
                        }
                        break;
                    }
                    case "GH_all": {
                        $products = FunctionsParser::GetProducts($parsed_link, 'GH_all');
                        for($i = 0; $i < 10; $i++) {
                            if($sendmessage_options['response'] == NULL) {
                                $sendmessage_options['response'] .= "/1" . "_GH_most_gifted " . $products[$i]['tproduct_title'] . "";
                            } else {
                                $sendmessage_options['response'] .= "\n" . "/" . strval($i+1) . "_GH_most_gifted" . " " . $products[$i]['tproduct_title'] . "";
                            }
                        }
                        break;
                    }
                    case "anonymous_message": {
                        $sendmessage_options['response'] .= "Ok. Now write message with next pattern:" . "\n" .
                                                            "/am %@username0% (%@username1% ...) - %message_text%";
                        break;
                    }
                    default: {
                        $sendmessage_options['response'] = "Good function (no)." . "\n" . "Watch carefully...";
                    }
                }
                break;
            }
            case "anonymous_message": {
                $command = str_replace("am ", "", $command);
                $command_am = explode(" - ", $command, 2);
                $command_ams = explode(" ", $command_am[0]);
                if(count($command_ams) == 1) {
                    $text_userid = strval($command_am[0]);
                    $text_userid = str_replace("@", "", $text_userid);
                    $users = FunctionsMessenger::GetUsers($messenger_link);
                    for($i = 0; $i < count($users); $i++) {
                        if($users[$i]['username'] == $text_userid) {
                            $to_userid = $users[$i]['userId'];
                            $to_username = $users[$i]['username'];
                        } else {
                            $to_userid = 0;
                            $to_username = 0;
                        }
                    }
                    //$messages = FunctionsParser::GetMessages($messenger_link, $user_id);
                    if($to_userid == 0) {
                        $sendmessage_options['response'] .= "Wrong username: @" . $text_userid . "\n";
                    } else {
                        $result = FunctionsMessenger::AddMessage($messenger_link, $command_am[1], $sendmessage_options['chatId'], $sendmessage_options['username'], $to_userid, $to_username);
                        if($result == TRUE) {
                            $sendmessage_options['response'] = "Message has been send to: @$to_username";
                        } else {
                            $sendmessage_options['response'] = "Message hasn't been send to: @$to_username";
                        }
                    }
                } elseif(count($command_ams) >= 2) {
                    $result = array();
                    $to_userid = array();
                    $to_username = array();
                    for($u = 0; $u < count($command_ams); $u++) {
                        $text_userid = strval($command_ams[$u]);
                        $text_userid = str_replace("@", "", $text_userid);
                        $users[$u] = FunctionsMessenger::GetUsers($messenger_link);
                        for($i = 0; $i < count($users[$u]); $i++) {
                            if($users[$u][$i]['username'] == $text_userid) {
                                $to_userid[$u] = $users[$u][$i]['userId'];
                                $to_username[$u] = $users[$u][$i]['username'];
                            } else {
                                $to_userid[$u] = 0;
                                $to_username[$u] = 0;
                            }
                        }
                        if($to_userid[$u] == 0) {
                            $sendmessage_options['response'] .= "Wrong username: @" . $text_userid . "\n";
                        } else {
                            //$messages = FunctionsParser::GetMessages($messenger_link, $user_id);
                            $result[$u] = FunctionsMessenger::AddMessage($messenger_link, $command_am[1], $sendmessage_options['chatId'], $sendmessage_options['username'], $to_userid[$u], $to_username[$u]);
                            if($result[$u] == TRUE) {
                                $sendmessage_options['response'] .= "Message has been send to: @" . $to_username[$u] . "\n";
                            } else {
                                $sendmessage_options['response'] .= "Message hasn't been send to: @" . $to_username[$u] . "\n";
                            }
                        }
                    }
                } else {
                    $sendmessage_options['response'] = "Try again with pattern, please";
                }
                break;
            }
            case "badcommand": {
                $sendmessage_options['response'] = "Something goes wrong... Again...";
                break;
            }
            default: {
                $sendmessage_options['response'] = "Try again, please";
            }
        }
        return $sendmessage_options;
    }
}
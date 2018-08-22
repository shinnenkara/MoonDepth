<?php
namespace TelegramServer\Methods;

use Parser\cURL\cURLOptions;
use Parser\Database\Functions;
use Parser\Database\Including;

class SetOptionsFrom {

    public static function Command($sendmessage_options = NULL, $command = NULL, $command_type = "badcommand", $products = NULL) {

        $today_date = intval(date("ymd") . "0000");
        $current_weekstart_date = intval(date("ymd") . "0000") - ((intval(date("N")) - 1) * 10000 );

        switch($command_type) {
            case "integer": {
                $command = explode("_", $command, 2);
                $parsed_link = Including::GetLink();
                $products = Functions::GetProducts($parsed_link, '' . $command[1] . '');
                if($command[0] <= count($products)) {
                    $sendmessage_options['response'] .= "Price: $" . $products[intval($command[0])-1]['tproduct_price'] . "\n";
                    if($products[intval($command[0])-1]['tproduct_prime'] == 1) {
                        $sendmessage_options['response'] .= "Prime account" . "\n";
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
                        $sendmessage_options['response'] = "Nice to meet you";
                        break;
                    }
                    case "departments": {
                        $sendmessage_options['response'] .= "/game_hardware" . "\n" . "...%in_development%...";
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
                        $parsed_link = Including::GetLink();
                        $products = Functions::GetProducts($parsed_link, 'GH_best_sellers');
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
                        $parsed_link = Including::GetLink();
                        $products = Functions::GetProducts($parsed_link, 'GH_hot_new_releases');
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
                        $parsed_link = Including::GetLink();
                        $products = Functions::GetProducts($parsed_link, 'GH_top_rated');
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
                        $parsed_link = Including::GetLink();
                        $products = Functions::GetProducts($parsed_link, 'GH_most_wished_for');
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
                        $parsed_link = Including::GetLink();
                        $products = Functions::GetProducts($parsed_link, 'GH_most_gifted');
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
                        $sendmessage_options['response'] .= "...%in_development%..." . "";
                        break;
                    }
                    /*case "week_releases": {
                        for($i = 0; $i < count($releases); $i++) {
                            if($releases[$i]['date'] >= $current_weekstart_date) {
                                if($sendmessage_options['response'] == NULL) {
                                    $sendmessage_options['response'] .= "/1 " . $releases[$i]['release'] . "";
                                } else {
                                    $sendmessage_options['response'] .= "\n" . "/" . strval($i+1) . " " . $releases[$i]['release'] . "";
                                }
                            }
                        }
                        break;
                    }*/
                    case "anonymous_message": {
                        $sendmessage_options['response'] .= "...%in_development%..." . "";
                        break;
                    }
                    default: {
                        $sendmessage_options['response'] = "Good function (no)." . "\n" . "Watch carefully...";
                    }
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
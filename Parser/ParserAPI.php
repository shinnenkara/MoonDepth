<?php
namespace Parser;
use Parser\cURL\cURLOptions;
use Parser\Database\FunctionsParser;
use Parser\Exceptions\DataExceptions;
use Parser\Exceptions\cURLExceptions;

class ParserAPI {

    public static function ParseNew($link = NULL) {

        $ch = curl_init(); // create cURL handle (ch)
        if (!$ch) {
            die("Couldn't initialize a cURL handle");
        }
        $infof = fopen("info.txt", "w+"); // open file to load data 
        $instance = "https://www.amazon.com/b/ref=s9_acsd_hfnv_hd_bw_birY_ct_x_ct00_w?_encoding=UTF8&node=172487&pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-4&pf_rd_r=4PCPFDRWC4YCF997FC71&pf_rd_t=101&pf_rd_p=fcb4f536-e749-5c31-91f7-ff7336983901&pf_rd_i=172456";
        $proxy = "185.185.44.2:21231";

        curl_setopt($ch, CURLOPT_FILE, $infof); // set some cURL options
        curl_setopt_array($ch, cURLOptions::SetOptions('' . $instance, '' . $proxy));
        $ret = curl_exec($ch); // execute

        if(cURLExceptions::IfEmpty($ret, $ch, $infof) == TRUE) {
            die("No HTTP code was returned");
        } else {
            $info = curl_getinfo($ch); 
            curl_close($ch); // close cURL handler
            fclose($infof); // close file
            $dom = str_get_html($ret);

            $pageBanner = $dom->find('.pageBanner', 0);
            $pageBanner = $pageBanner->find('h1', 0);
            $pageBanner = strval($pageBanner->plaintext);
            $pageBanner = str_replace(" ", "_", $pageBanner);
            $pageBanner = strtolower($pageBanner);

            $titles = $dom->find(".acswidget-carousel-redesign__heading");
            $title = array_fill(0, 5, "");
            $tamount = array_fill(0, 5, "");
            for($ti = 0; $ti < count($titles); $ti++) {
                $brackets = array("(", ")");
                $title_unexp[$ti] = trim(strval($titles[$ti]->plaintext));
                $title_exp[$ti] = explode(" ", $title_unexp[$ti]);
                for($exploded = 0; $exploded < (count($title_exp[$ti]) - 1); $exploded++) {
                    if($exploded == 0) {
                        $title[$ti] .= $title_exp[$ti][$exploded];
                    } else {
                        $title[$ti] .= "_" . $title_exp[$ti][$exploded];
                    }
                }
                $title[$ti] = strtolower($title[$ti]);
                $tamount[$ti] = intval(str_replace($brackets, "", $title_exp[$ti][count($title_exp[$ti]) - 1]));
            }

            $tproduct_image = array();
            $timage = $dom->find(".acswidget-carousel-redesign__product-image-container");
            for($ti = 0; $ti < count($timage); $ti++) {
                $tproduct_image[$ti] = $timage->src;
            }

            $tproduct_link = array();
            $tproduct_id = array();
            $tproduct_rate = array();
            $tlinks = $dom->find(".acswidget-carousel-redesign__product-title");
            for($ti = 0; $ti < count($tlinks); $ti++) {
                $tproduct_l = "https://www.amazon.com" . $tlinks[$ti]->href;
                $tproduct_l = explode("/ref", $tproduct_l);
                $tproduct_lid = explode("product/", $tproduct_l[0]);
                $tproduct_link[$ti] = $tproduct_l[0];
                $tproduct_id[$ti] = $tproduct_lid[1];
                /*$toptions = array(CURLOPT_URL => $tproduct_link[$ti], 
                    CURLOPT_REFERER => "https://www.amazon.com",
                    CURLOPT_PROXY => $proxy,
                    CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36 Edge/16.16299",
                    CURLOPT_HEADER => 0,
                    CURLOPT_FOLLOWLOCATION => 1,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_TIMEOUT => 60);
                $tch = curl_init(); // create cURL handle (ch) 
                curl_setopt_array($tch, $toptions);
                $trate = curl_exec($tch); // execute
                $trate_dom = str_get_html($trate);
                $tproduct_r = $trate_dom->find(".reviewCountTextLinkedHistogram", 0);
                $tproduct_rate[$ti] = $tproduct_r->plaintext;*/
            }
            $tproduct_price = array();
            $tprices = $dom->find(".acswidget-carousel-redesign__product-price");
            for($ti = 0; $ti < count($tlinks); $ti++) {
                $tproduct_p = trim(strval($tprices[$ti]->plaintext));
                $tproduct_p = explode(" ", $tproduct_p);
                $tproduct_pr = str_replace("$", "", $tproduct_p[0]);
                $tproduct_price[$ti] = floatval($tproduct_pr);
            }
            $tproduct_prime = array();
            $tprimes = $dom->find(".acswidget-carousel-redesign__product-price i");
            for($ti = 0; $ti < count($tprimes); $ti++) {
                if($tprimes[$ti] != NULL) {
                    $tproduct_prime[$ti] = 1;
                } else {
                    $tproduct_prime[$ti] = 0;
                }
            }
            $tproduct_title = array();
            $ttitles = $dom->find(".acswidget-carousel-redesign__product-title");
            for($ti = 0; $ti < count($ttitles); $ti++) {
                $tproduct_title[$ti] = $ttitles[$ti]->title;
            }
            $tproduct_image = array();
            $timage = $dom->find(".acswidget-carousel-redesign__product-image-container");
            for($ti = 0; $ti < count($timage); $ti++) {
                $tproduct_image[$ti] = $timage[$ti]->find("img", 0)->src;
            }

            $table = "";
            for($ti = 0, $i = 0, $fa = 0; $ti < count($title); $ti++) {
                $fa += intval($tamount[$ti]);
                switch($title[$ti]) {
                    case "best_sellers": {
                        $table = "GH_best_sellers";
                        break;
                    }
                    case "hot_new_releases": {
                        $table = "GH_hot_new_releases";
                        break;
                    }
                    case "top_rated": {
                        $table = "GH_top_rated";
                        break;
                    }
                    case "most_wished_for": {
                        $table = "GH_most_wished_for";
                        break;
                    }
                    case "most_gifted": {
                        $table = "GH_most_gifted";
                        break;
                    }
                    default: {
                        $table = "GH_all";
                    }
                }
                for($i; $i < $fa; $i++) {
                    $tproduct_date[$i] = intval(date("ymdHi"));
                    FunctionsParser::SetProducts($link, $table,
                                        $tproduct_id[$i],
                                        $tproduct_title[$i],
                                        $tproduct_link[$i],
                                        $tproduct_image[$i],
                                        $tproduct_price[$i],
                                        $tproduct_rate[$i],
                                        $tproduct_prime[$i],
                                        $tproduct_date[$i]);
                }
            }

            $tproduct_title = array();
            $tproduct_link = array();
            $tproduct_id = array();
            $ttitles = $dom->find(".s-color-twister-title-link");
            for($ti = 0; $ti < count($ttitles); $ti++) {
                $tproduct_title[$ti] = $ttitles[$ti]->title;
                $tproduct_l = $ttitles[$ti]->href;
                $tproduct_l = explode("/ref", $tproduct_l);
                $tproduct_lid = explode("dp/", $tproduct_l[0]);
                $tproduct_link[$ti] = $tproduct_l[0];
                $tproduct_id[$ti] = $tproduct_lid[1];
            }
            $tproduct_price = array();
            $tprices = $dom->find(".sx-price-large");
            for($ti = 0; $ti < count($tprices); $ti++) {
                $tproduct_p = trim(strval($tprices[$ti]->plaintext));
                $tproduct_p = trim(str_replace("$", "", $tproduct_p));
                for($i = 0, $k = 0; $i < (strlen($tproduct_p)); $i++) {
                    if($tproduct_p[$i] == " ") {
                        if($k == 0) {
                            $tproduct_p[$i] = ".";
                            $k++;
                        }
                        break;
                    }
                }
                $tproduct_p = explode(".", $tproduct_p);
                $tproduct_pr = trim($tproduct_p[0]) . "." . trim($tproduct_p[1]);
                if($ti == count($tprices) - 1) {
                    $tproduct_price[0] = floatval($tproduct_pr);
                } else {
                    $tproduct_price[$ti + 1] = floatval($tproduct_pr);
                }
            }
            $tproduct_prime = array();
            /*$tprimes = $dom->find(".a-icon.a-icon-prime.a-icon-small.s-align-text-bottom");
            for($ti = 0; $ti < count($tprimes); $ti++) {
                if($tprimes[$ti] != NULL) {
                    $tproduct_prime[$ti] = 1;
                } else {
                    $tproduct_prime[$ti] = 0;
                }
            }*/
            /*$tproduct_image = array();
            $timage = $dom->find(".s-position-relative");
            for($ti = 0; $ti < count($timage); $ti++) {
                $tproduct_image[$ti] = $timage[$ti]->find("img", 0)->src;
            }
            $tproduct_rate = array();
            $trate = $dom->find("div=[class='s-item-container']");
            for($ti = 0; $ti < count($trate); $ti++) {
                $tproduct_rate[$ti] = trim($trate[$ti]->find(".a-icon-star", 0)->plaintext);
            }*/
            
            /*echo $pageBanner . "<br/><br/>";
            for($i = 0; $i < count($tproduct_title); $i++) {
                if($tproduct_id[$i] == NULL) {
                    continue;
                } else {
                    echo ($i + 1) . ". " . $tproduct_link[$i] . " | " . $tproduct_price[$i];
                    if($tproduct_prime[$i] == 1) {
                        echo " | " . "Prime account";
                    }
                    echo " | " . $tproduct_id[$i] . " | "
                    . $tproduct_title[$i] . " | "
                    . $tproduct_image[$i] . " | "
                    //. $tproduct_reviews[$i] . " | "
                    . $tproduct_rate[$i]
                    . "<br/>";
                }
            }
            echo "<br/>";*/

            $table = "GH_all";
            for($i = 0; $i < count($tproduct_title); $i++) {
                $tproduct_date[$i] = intval(date("ymdHi"));
                $tproduct_prime[$i] = 0;
                if($tproduct_id[$i] == NULL) {
                    continue;
                } else {
                    FunctionsParser::SetProducts($link, $table,
                                        $tproduct_id[$i],
                                        $tproduct_title[$i],
                                        $tproduct_link[$i],
                                        $tproduct_image[$i],
                                        $tproduct_price[$i],
                                        $tproduct_rate[$i],
                                        $tproduct_prime[$i],
                                        $tproduct_date[$i]);
                }
            }

            #   $tproduct_id[]
            #   $tproduct_title[]
            #   $tproduct_link[]
            #   $tproduct_price[]
            #   $tproduct_prime[]

            /*echo $pageBanner . "<br/><br/>";
            for($ti = 0; $ti < count($title); $ti++) {
                echo $title[$ti] . "<br/>";
                for($i = 0; $i < $tamount[$ti]; $i++) {
                    echo ($i + 1) . ". " . $tproduct_link[$i] . " | " . $tproduct_price[$i];
                    if($tproduct_prime[$i] == 1) {
                        echo " | " . "Prime account";
                    }
                    echo " | " . $tproduct_id[$i] . " | " . $tproduct_title[$i] . "<br/>";
                }
                echo "<br/>";
            }*/
            //FunctionsParser::SetIds($link);*/
        }
    }
}
?>
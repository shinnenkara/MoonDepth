<?php
namespace Parser;
use Parser\cURL\cURLOptions;
use Parser\Database\Functions;
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

            $tproduct_link = array();
            $tproduct_id = array();
            $tlinks = $dom->find(".acswidget-carousel-redesign__product-title");
            for($ti = 0; $ti < count($tlinks); $ti++) {
                $tproduct_l = "https://www.amazon.com" . $tlinks[$ti]->href;
                $tproduct_l = explode("/ref", $tproduct_l);
                $tproduct_lid = explode("product/", $tproduct_l[0]);
                $tproduct_link[$ti] = $tproduct_l[0];
                $tproduct_id[$ti] = $tproduct_lid[1];
            }
            $tproduct_price = array();
            $tprices = $dom->find(".acswidget-carousel-redesign__product-price");
            for($ti = 0; $ti < count($tlinks); $ti++) {
                $tproduct_p = trim(strval($tprices[$ti]->plaintext));
                $tproduct_p = explode(" ", $tproduct_p);
                $tproduct_pr = str_replace("$", "", $tproduct_p[0]);
                $tproduct_price[$ti] = floatval($tproduct_pr[1]);
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
                    Functions::SetProducts($link, $table, $tproduct_id[$i], $tproduct_title[$i], $tproduct_link[$i], $tproduct_price[$i], $tproduct_prime[$i]);
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
            //Functions::SetIds($link);*/
        }
    }
}
?>
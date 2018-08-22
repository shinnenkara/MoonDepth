<?php
namespace Parser\cURL;

        class cURLOptions{

                public static function SetOptions($instance_url, $proxy_url){

                    $options = array(CURLOPT_URL => $instance_url, 
                                    CURLOPT_PROXY => $proxy_url,
                                    CURLOPT_REFERER => "https://www.amazon.com/ref=nav_logo",
                                    CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/68.0.3440.106 Safari/537.36",
                                    CURLOPT_HEADER => 0,
                                    CURLOPT_FOLLOWLOCATION => 1,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_TIMEOUT => 10);

                        return $options; 
                }
            
        }

?>
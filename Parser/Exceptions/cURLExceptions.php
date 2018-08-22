<?php
namespace Parser\Exceptions;

        class cURLExceptions{
        
                public static function IfEmpty($executed = NULL, $cURLhandler = NULL, $opened_file = NULL){

                        if(empty($executed)) {
                                die(curl_error($cURLhandler)); // some kind of an error happened
                                curl_close($cURLhandler); // close cURL handler
                                fclose($opened_file); // close file
                                
                                return TRUE;
                        } else {
                                return FALSE;
                        }
                }
        }
?>
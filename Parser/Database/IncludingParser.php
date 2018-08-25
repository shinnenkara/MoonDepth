<?php
namespace Parser\Database;

class IncludingParser {
    
    public static function GetLink() {

        #$url = "";
        $server = 'localhost';
        $username = 'root';
        $password = '';
        $db = 'test';

        $link = mysqli_connect($server, $username, $password, $db);

        if(mysqli_connect_errno()) {
            echo "Error (" . mysqli_connect_errno() . "): " . mysqli_connect_error();
            exit();
        }

        return $link;
    }   
}

?>
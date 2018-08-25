<?php
namespace Messenger\Database;

class IncludingMessenger { 

    public static function GetLink() {

        #$url = "";
        $server = 'localhost';
        $username = 'root';
        $password = '';
        $db = 'userdb';

        $link = mysqli_connect($server, $username, $password, $db);

        if(mysqli_connect_errno()) {
            echo "Error (" . mysqli_connect_errno() . "): " . mysqli_connect_error();
            exit();
        }

        return $link;
    }
}
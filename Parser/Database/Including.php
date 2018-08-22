<?php
namespace Parser\Database;

class Including {
    
    public static function GetLink() {

        #$url = "";
        $server = 'localhost';
        $username = 'id6861252_karasick';
        $password = 'm91tnaya';
        $db = 'id6861252_projectfortest001db';

        $link = mysqli_connect($server, $username, $password, $db);

        if(mysqli_connect_errno()) {
            echo "Error (" . mysqli_connect_errno() . "): " . mysqli_connect_error();
            exit();
        }

        return $link;
    }   
}

?>
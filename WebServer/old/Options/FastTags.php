<?php
namespace WebServer;

class Page {

    public static function TopHead($head = "%NULL%"){

        echo "<html><head><title>" . $head . "</title></head><body>";
    }

    public static function Top(){

        echo "<html><body>";
    }

    public static function Bottom(){

        echo "</body></html>";
    }
}
?>
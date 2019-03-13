<?php
use \WebServer\php\Database;
$link = Database::GetLinkToDB();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="generator" content="Google Web Designer 4.2.0.0802">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <title>moondepth</title>
    <link rel="shortcut icon" type="image/png" href="WebServer/img/outline_brightness_2_white_48dp.png"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="WebServer/css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <link href="WebServer/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>
<body>
    <header>
        <nav id="top-nav" class="grey darken-3">
            <div class="container">
                <div class="nav-wrapper">
                    <div class="row">
                        <div class="col s12 m10 offset-m1">
                        <a href="#" data-target="nav-mobile" class="top-nav sidenav-trigger full hide-on-large-only" style="transform: translateX(0px);">
                        <i class="material-icons">menu</i></a>
                        <a id="logo-container" href="#" class="brand-logo">
                            <img style="width: 15%; height: 15%;" src="WebServer/img/outline_brightness_2_white_48dp.png"/>moondepth</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <ul id="nav-mobile" class="sidenav sidenav-fixed">
            <ul class="section table-of-contents">
                <!--
                <li><a href="#introduction-banner">Introduction</a></li>
                -->
                <?php
                    $boards = Database::GetBoards($link);
                    foreach($boards as $board) {
                        $headline = $board["headline"];
                        $description = $board["description"];
                        echo "<li><a class = \"white-text text-navbar\" href = \"board?headline=" . $headline . "\" title=\"" . $description . "\">" 
                        . "/" . $headline . "/ - " . $description . "</a></li>";
                    }
                ?>
                <!--
                <li><a href="#afterword-banner">Afterword</a></li>
                -->
            </ul>
        </ul>
    </header>
    <main>
        <div class="fixed-action-btn">
            <button id="toTop-button" class="btn-floating btn-large grey darken-4" onclick="scrollToTop()" style="display: none">
            <i class="large material-icons">arrow_drop_up</i></button>
        </div>
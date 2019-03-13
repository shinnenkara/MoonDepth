<?php
include "WebServer/php/Database.php";
include "WebServer/MainPage.php";
ini_set('error_reporting', E_ALL);
use \WebServer\php\Database;
$link = Database::GetLinkToDB();
?>
<div class="section no-pad-bot scrollspy" id="introduction-banner">
    <div class="container white-text">
        <h2 class="center">
            <div>Welcome fellow man</div>
        </h2>
    </div>
</div>
<div class="container white-text">
    <div class="row">
        <div class="col s12 m6 l4 center"><h3><i>Big Board:</i></h3><br>
            <?php
            $big_board = Database::FindBigBoard($boards);
            echo "<a class = \"white-text text-navbar\" href = \"board?headline=" . $big_board["headline"] . "\" title=\"" . $big_board["description"] . "\">"
            . "/" . $big_board["headline"] . "/ - " . $big_board["description"] . "<br>";
            ?>
        </div>
        <div class="col s12 m6 l4 center"><h3><i>Best board:</i></h3><br>
            <?php
            echo "<a class = \"white-text text-navbar\" href = \"board?headline=" . $boards[0]["headline"] . "\" title=\"" . $boards[0]["description"] . "\">"
            . "/" . $boards[0]["headline"] . "/ - " . $boards[0]["description"] . "<br>";
            ?>
        </div>
        <div class="col s12 m12 l4 center"><h3><i>Your board:</i></h3><br>
            <?php
            $rand_board = rand(0,31);
            if($rand_board == 0) {
                echo "Look left" . "<br>";
            } else {
                echo "<a class = \"white-text text-navbar\" href = \"board?headline=" .  $boards[$rand_board]["headline"] . "\" title=\"" . $boards[$rand_board]["description"] . "\">"
                . "/" . $boards[$rand_board]["headline"] . "/ - " . $boards[$rand_board]["description"] . "<br>";
            }
            ?>
        </div>
    </div>
</div>
<br><br><br><br><br><br><br>
<?php
include 'WebServer/Footer.html';
?>
<script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous">
</script>
<script src="WebServer/js/materialize.min.js">
</script>
<script src="WebServer/js/init.js">
</script>
<script type = "text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
    var SSpy_elements = document.querySelectorAll('.scrollspy');
    var SSpy_options = {throttle: 100, scrollOffset: 5, activeClass: "active"};
    var instances = M.ScrollSpy.init(SSpy_elements, SSpy_options);
    });
</script>
<script type = "text/javascript">
    function scrollToTop() {
        var graphics = document.getElementById("top-nav");
        graphics.scrollIntoView({block: "start", behavior: "smooth"});
    }
    window.onscroll = function() {
        var scrolled = window.pageYOffset || document.documentElement.scrollTop;
        var toTopButton = document.getElementById("toTop-button");
        if (toTopButton.style.display === "none" && scrolled >= 0) {
            $(toTopButton).toggle();
        } else if(toTopButton.style.display !== "none" && scrolled == 0) {
            $(toTopButton).toggle();
        }
    }
</script>
</body>
</html>
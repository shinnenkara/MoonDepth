<?php
include "WebServer/php/Database.php";
include "WebServer/MainPage.php";
ini_set('error_reporting', E_ALL);
use \WebServer\php\Database;
$link = Database::GetLinkToDB();
if(isset($_GET["id"])) {
    if($_GET["id"] != "") {
        $thread_id = $_GET["id"];
    } else {
        $thread_id = "1";
    }
} else {
    $thread_id = "1";
}
?>
<div class="container white-text" style="text-align: left; padding: .75rem .75rem 1.5rem;">
    <?php
    $threads = Database::GetThreads($link, "", $thread_id);
    $headline = $threads[0]["bid"];
    echo "<a id=\"gobackButton\" class=\"waves-effect waves-light grey darken-3 btn-large\" href=\"board?headline=" . 
        $headline . "\"><strong>RETURN TO THE BOARD</strong></a>";
    ?>
</div>
<div class="container white-text threads">
    <?php
    foreach($threads as $thread) {
        $thread_id = $thread["id"];
        $topic = $thread["topic"];
        $subject_text = $thread["subject_text"];
        $creation_date = $thread["creation_date"];
        $creation_date = str_replace("-", ".", strval($creation_date));
        $creation_date = str_replace(" ", "-", strval($creation_date));
        $creator = Database::GetUsers($link, $thread["uid"]);
        $creator_username = $creator[0]["username"];
        echo "<div class=\"col thread\" style=\"padding: 1.5rem .75rem;\">
            <div class=\"thread-head\">
                <i class=\"material-icons hidethread-icons\">arrow_drop_down</i>
                <h5 style=\"display: inline\"><strong>" . $topic . "</strong></h5>
                <h5 class=\"grey-text text-lighten-1\" style=\"display: inline\">" . $creator_username . "</h5>
                <h5 style=\"display: inline\">" . $creation_date . "</h5>
                <a class=\"white-text\" href=\"thread?id=" . 
                    $thread_id . "\"><h5 style=\"display: inline\">No. " . $thread_id . "</h5></a>
            </div>
            <div class=\"thread-body\">
                </br>
                <div class=\"container\">
                    <h5 style=\"display: inline\">" . $subject_text . "</h5></span>
                </div>
                </br>
            </div>
        </div>";
    }
    ?>
</div>
<div class="container white-text center" style="padding: 0rem .75rem;">
    <a id="creationButton" class="waves-effect waves-light grey darken-3 btn-large" onclick="creationToggle()"><strong>MAKE REPLY</strong></a>
</div>
<div id="shadow" class="container white-text center" style="display: none; width: 50%; padding: 1.5rem .75rem;">
    <?php
    echo "<form id=\"message-form\" class=\"col s12\" method=\"post\" action=\"WebServer/php/SubmitForm\">";
    ?>
        <div class="row">
            <div class="input-field col s12 m6">
                <input id="username_input" class="white-text" type="text" name="username" value="Anonymous" data-length="20">
                <label for="username_input">Your username</label>
                <span class="helper-text left">You can get recognized</span>
            </div>
            <div class="input-field col s12 m6" style="display: none;">
                <input id="response_to_input" class="white-text" type="text" name="response_to" data-length="10">
                <label for="response_to_input">Response to</label>
            </div>
            <div class="input-field col s12">
                <textarea id="message_text_input" class="materialize-textarea white-text" name="message_text" data-length="120"></textarea>
                <label for="message_text_input">Message text</label>
            </div>
            <div class="input-field col s12">
                <button id="submit_input" class="white-text waves-effect waves-light grey darken-3 btn-large" type="submit" name="submit" value="send">Send</button>
            </div>
            <div class="submit-check col s12">
            </div>
        </div>
    </form>
</div>
<div id="messages" class="container white-text">
    <?php
    echo "<div class=\"row\">";
    $messages = Database::GetMessages($link, $thread_id);
    foreach($messages as $message) {
        $message_id = $message["id"];
        $response_to = $message["response_to"];
        $message_text = $message["text"];
        $message_date = $message["message_date"];
        $message_date = str_replace("-", ".", strval($message_date));
        $message_date = str_replace(" ", "-", strval($message_date));
        $visitor = Database::GetUsers($link, $message["uid"]);
        $visitor_username = $visitor[0]["username"];
        echo "<div class=\"col message\" style=\"width: 90%; padding: 1.5rem .75rem;\">
            <div class=\"message-head\">
                <i class=\"material-icons hidemessage-icons\">arrow_drop_down</i>
                <h5 class=\"grey-text text-lighten-1\" style=\"display: inline\">" . $visitor_username . "</h5>
                <h5 style=\"display: inline\">" . $message_date . "</h5>
                <a class=\"white-text\" href=\"http://localhost/projects/GitHub/MoonDepth/thread?id=" . 
                $thread_id . "\"><h5 style=\"display: inline\">No. " . $message_id . "</h5></a>
            </div>
            <div class=\"message-body\">
                </br>
                <div class=\"container\">
                    <h5 style=\"display: inline\">" . $message_text . "</h5></span>
                </div>
                </br>
            </div>
        </div>";
    }
    echo "</div>";
    ?>
</div>
</br></br></br></br></br>
</br></br></br></br></br>
<?php
include 'WebServer/Footer.html';
?>
<script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous">
</script>
<script src="WebServer/js/materialize.js">
</script>
<script src="WebServer/js/init.js">
</script>
<script type = "text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
    var SSpy_elements = document.querySelectorAll('.scrollspy');
    var SSpy_options = {throttle: 100, scrollOffset: 5, activeClass: "active"};
    var instances = M.ScrollSpy.init(SSpy_elements, SSpy_options);
    });
    $(document).ready(function() {
        $('input#username_input, input#topic_input, textarea#message_text_input').characterCounter();
    });
    M.textareaAutoResize($('#message_text_input'));
</script>
<script type = "text/javascript">
    function scrollToTop() {
        $(document).ready(function() {
            var graphics = document.getElementById("top-nav");
            graphics.scrollIntoView({block: "start", behavior: "smooth"});
        });
    }
    function creationToggle() {
        $(document).ready(function() {
            var shadow = document.getElementById("shadow");
            $(shadow).slideToggle( "slow", function() {
                var isHide = document.getElementById("creationButton").innerText;
                if(isHide == "MAKE REPLY") {
                    document.getElementById("creationButton").innerHTML = "<strong>HIDE REPLYING</strong>";
                } else {
                    document.getElementById("creationButton").innerHTML = "<strong>MAKE REPLY</strong>";
                }
            });
        });
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
    $(document).ready(function() {
        $("#message-form").submit(function(event) {
            event.preventDefault();
            $(".submit-check").load("WebServer/php/SubmitForm", {
                headline: "<?php echo $headline; ?>",
                thread_id: "<?php echo $thread_id; ?>",
                username: $("#username_input").val(),
                response_to: $("#response_to_input").val(),
                message_text: $("#message_text_input").val(),
                submit_message: $("#submit_input").val()
            }, function() {
                $("#messages").load("WebServer/php/ThreadView", {
                    thread_id: "<?php echo $thread_id; ?>"
                });
            });
        });
    });
    $(document).ready(function() {
        var logo = document.getElementById("logo-container");
        logo.href = "http://localhost/projects/GitHub/MoonDepth";
    });
</script>
</body>
</html>
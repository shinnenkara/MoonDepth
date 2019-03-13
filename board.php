<?php
include "WebServer/php/Database.php";
include "WebServer/MainPage.php";
ini_set('error_reporting', E_ALL);
use \WebServer\php\Database;
$link = Database::GetLinkToDB();
if(isset($_GET["headline"])) {
    if($_GET["headline"] != "") {
        $headline = $_GET["headline"];
    } else {
        $headline = "a";
    }
} else {
    $headline = "a";
}
?>
<div id="welcome" class="container white-text center">
    <?php
    echo "<h2>Welcome to /" . $headline . "/</h2>" . "<br />";
    ?>
    <a id="creationButton" class="waves-effect waves-light grey darken-3 btn-large" onclick="creationToggle()"><strong>CREATE THREAD</strong></a>
</div>
<div id="shadow" class="container white-text center" style="display: none; width: 50%;">
    <?php
    echo "<form id=\"thread-form\" class=\"col s12\" method=\"post\" action=\"WebServer/php/SubmitForm\">";
    ?>
        <div class="row">
            <div class="input-field col s12 m6">
                <input id="username_input" class="white-text" type="text" value="Anonymous" name="username" data-length="20">
                <label for="username_input">Your username</label>
                <span class="helper-text grey-text text-darken-2 left">You can get recognized</span>
            </div>
            <div class="input-field col s12 m6">
                <input id="topic_input" class="white-text" type="text" name="topic" data-length="40">
                <label for="topic_input">Topic</label>
            </div>
            <div class="input-field col s12">
                <textarea id="subject_text_input" class="materialize-textarea white-text" name="subject_text" data-length="120"></textarea>
                <label for="subject_text_input">Subject text</label>
            </div>
            <div class="input-field col s12">
                <div class="file-field input-field">
                    <div class="btn">
                        <span>File</span>
                        <input id="file_array_input" type="file" name="file_array_input" multiple>
                    </div>
                    <div class="file-path-wrapper">
                        <input id="file_path" class="file-path validate white-text" type="text" name="file_array"> <!-- placeholder="Upload one or more files" -->
                        <span class="helper-text grey-text text-darken-2 left">Upload up to three files</span>
                    </div>
                </div>
            </div>
            <div class="input-field col s12">
                <button id="submit_input" class="white-text waves-effect waves-light grey darken-3 btn-large" type="submit" name="submit" value="send">Send</button>
            </div>
            <div class="submit-check col s12">
            </div>
        </div>
    </form>
</div>
<div id="thread" class="container white-text">
    <div class="row">
        <?php
        $threads = Database::GetThreads($link, $headline);
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
                    <a class=\"white-text\" href=\"thread?id=" . $thread_id . "\"><h5 style=\"display: inline\">No. " . $thread_id . "</h5></a>
                    <a class=\"white-text\" href=\"thread?id=" . $thread_id . "\"><h5 style=\"display: inline\">[<span class=\"grey-text text-lighten-1\">Reply</span>]</h5></a>
                </div>
                <div class=\"thread-body\">
                    </br>
                    <div class=\"container\">
                        <h5 style=\"display: inline\">" . $subject_text . "</h5></span>
                    </div>
                    </br>
                </div>";
                echo "<div class=\"container white-text messages\">
                    <div class=\"row\">";
                        $messages = Database::GetMessages($link, $thread_id);
                        $messages_amount = count($messages);
                        if($messages_amount < 3) {
                            $first_message = 0;
                        } else {
                            $first_message = $messages_amount - 3;
                        }
                        for($i = $first_message; $i < $messages_amount; $i++) {
                            $message = $messages[$i];
                            $message_id = $message["id"];
                            $response_to = $message["response_to"];
                            $message_text = $message["text"];
                            $message_date = $message["message_date"];
                            $message_date = str_replace("-", ".", strval($message_date));
                            $message_date = str_replace(" ", "-", strval($message_date));
                            $visitor = Database::GetUsers($link, $message["uid"]);
                            $visitor_username = $creator[0]["username"];
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
                    echo "</div>
                </div>
            </div>";
        }
        ?>
    </div>
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
        $('input#username_input, input#topic_input, textarea#subject_text_input').characterCounter();
    });
    M.textareaAutoResize($('#subject_text_input'));
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
                if(isHide == "CREATE THREAD") {
                    document.getElementById("creationButton").innerHTML = "<strong>HIDE THREAD</strong>";
                } else {
                    document.getElementById("creationButton").innerHTML = "<strong>CREATE THREAD</strong>";
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
        $("#thread-form").submit(function(event) {
            event.preventDefault();
            var file_data = $('#file_array_input').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file_array', file_data);
            //new Response(form_data).text().then(console.log);
            //console.log(...form_data);
            $(".submit-check").load("WebServer/php/SubmitForm", {
                headline: "<?php echo $headline; ?>",
                username: $("#username_input").val(),
                topic: $("#topic_input").val(),
                subject_text: $("#subject_text_input").val(),
                submit_thread: $("#submit_input").val()
            }, function() {
                $.ajax({
                    url: 'WebServer/php/SubmitForm',
                    data: form_data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST', // For jQuery < 1.9
                    success: function(form_data) {
                        new Response(form_data).text().then(console.log);
                    }
                });
                $("#thread").load("WebServer/php/BoardView", {
                    headline: "<?php echo $headline; ?>"
                });
            });
        });
    });
    $(document).ready(function() {
        var logo = document.getElementById("logo-container");
        logo.href = "http://localhost/projects/GitHub/MoonDepth";
    });
    var formData = new FormData();
    formData.append('key1', 'value1');
    formData.append('key2', 'value2');
    new Response(formData).text().then(console.log);
</script>
</body>
</html>
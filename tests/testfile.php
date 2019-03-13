<!doctype html>
<html>
<head lang="en">
    <meta charset="utf-8">
    <title>Ajax File Upload with jQuery and PHP</title>
</head>
<body>
    <!--
    <form id="thread-form" class="col s12" method="post" action="WebServer/php/SubmitForm">
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
                        <input id="file_path" class="file-path validate white-text" type="text" name="file_array">
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
    -->
    <form id="form" method="post" action="upload">
        <input id="sortpicture" type="file" name="sortpic" />
        <button id="upload" type="submit" name="submit" value="send">Upload</button>
        <div class="check">
        </div>
    </form>

    <script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous">
    </script>
    <script>
        /*
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
        */
        //$(document).ready(function() {
            $("#form").submit(function(event) {
                //$('#upload').on('click', function() {
                    $(".check").load("upload", {
                    check: true

                    }, function() {
                        var file_data = $('#sortpicture').prop('files')[0];   
                        var form_data = new FormData();                  
                        form_data.append('file', file_data);                          
                        $.ajax({
                            url: 'upload', // point to server-side PHP script
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: form_data,                         
                            type: 'post',
                            success: function(php_script_response){
                                alert(php_script_response); // display response from the PHP script, if any
                            }
                        });
                    });
                //});
            });
        //});
    </script>
</body>
</html>

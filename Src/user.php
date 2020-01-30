<!DOCTYPE html>
<html>
    <head>
        <title>Userpage</title>
    </head>
    <body>
        <header>
        </header>
        <body class="flex-container">
            <!-- 
                The body is made up of 
                    A top div (image & blog name)
                    A middle div with
                        A left div for the user image, username, and following buttons
                        A right div for the user posts div
            -->
            <div>
                <h1>Blog Name</h1>
            </div>
            <div>
                <div>
                    <div>
                        <img>
                        <p>Username</p>
                        <p>Followers</p>
                        <form id="follow-form">
                            <input hidden name="username" value="<?php echo($_GET["username"]);?>">
                            <button id="follow-button" type="button">Subscribe</button>
                        </form>
                    </div>
                </div>
                <div id="body">
                    
                </div>
            </div>
        </body>
        <footer>
            <p>View the code on <a href="https://github.com/Nathan-Nesbitt/fallr">github</a></p>
        </footer>
        <script
			  src="https://code.jquery.com/jquery-3.4.1.min.js"
			  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
			  crossorigin="anonymous">
        </script>

        <!-- Cookie CDN -->
        <script src="https://cdn.jsdelivr.net/npm/js-cookie@beta/dist/js.cookie.min.js"></script>
        
        <script>
            $( document ).ready(
                $.ajax({
                    url: 'http://localhost/fallrAPI/feed/',
                    type: 'POST',
                    dataType: 'JSON',
                    data: "userID=" + Cookies.get("loggedIn").split(":")[0],
                    success: function(data) {
                        var newPosts = "";
                        $.each(data, function(key, value){
                            newPosts += "<section><form><input type='hidden' name='postID' class='postID' value='" + value.postID + "'>";
                            newPosts += "<div><div><img></div>";
                            newPosts += "<div><p>" + value.postName + "</p></div></div>";
                            newPosts += "<div><p>" + value.postContent + "</p></div>";
                            newPosts += "<div><button class='like-button' type='button'>Like</button><button class='view-comments-button' type='button'>View Comments</button></div></form></section>";
                        });
                        $('#body').html(newPosts);
                    }
                }),
            );

            $("#follow-button").click(function() {
                $.ajax({
                    url: 'http://localhost/fallrAPI/subscribe',
                    type: 'POST',
                    dataType:'text',
                    data: $('form').serialize(),
                    success: function(data) {
                        if(data == 1)
                            $("#follow-button").html("Unsubscribe");
                        else
                            $("#follow-button").html("Subscribe");
                    }
                });
            });

            $("#body").on('click','.like-button', function(event){
                // This selects the parent form for the button and submits the like for it //
                var button = this;
                $.ajax({
                    url: 'http://localhost/fallrAPI/likes',
                    type: 'POST',
                    dataType:'text',
                    data: 'postID=' + $(button).closest("form").find(".postID").val(),
                    success: function(data) {
                        if(data == 1)
                            $(button).html("Unlike");
                        else
                            $(button).html("Like");
                    }
                });
            });

            $("#body").on('click','.view-comments-button', function(event){
                // This selects the parent form for the button and submits the like for it //
                var button = this;
                $.ajax({
                    url: 'http://localhost/fallrAPI/comments',
                    type: 'POST',
                    dataType:'text',
                    data: 'postID=' + $(button).closest("form").find(".postID").val(),
                    success: function(data) {
                        if(data == 1)
                            $(button).html("Unlike");
                        else
                            $(button).html("Like");
                    }
                });
            });
        </script>
    </body>
</html>
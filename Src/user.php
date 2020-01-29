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
                            <input id="follow-button" type="button" value="Follow"></input>
                        </form>
                    </div>
                </div>
                <div>
                    <!-- This is an individual post, which can be duplicated n times -->
                    <section>
                        <!-- This is the 'head of the post' which includes the title and the image -->
                        <div>
                            <div>
                                <img>
                            </div>
                            <div>
                                <p>Title of the blog post.</p>
                            </div>
                        </div>
                        <!-- This is the content in the post -->
                        <div>
                            <p>This is the content of my post.</p>
                        </div>                        
                    </section>
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
            $("#follow-button").click(function() {
                $.ajax({
                    url: 'http://localhost/fallrAPI/subscribe',
                    type: 'POST',
                    dataType: 'JSON',
                    data: $('#follow-form').serialize(),
                    success: function(data) {
                        console.log("success");
                    }
                });
            }); 
        </script>
    </body>
</html>
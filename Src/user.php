<!DOCTYPE html>
<html>
    <head>
        <title><?php echo($_GET["username"]);?>'s Userpage - Fallr</title>
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
        
        <script>
            document.addEventListener('DOMContentLoaded', function(){
                xhttp = new XMLHttpRequest();
                
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        var data = xhttp.responseText;
                        data = JSON.parse(data);
                        console.log(data);

                        var newPosts = "";
                        data.forEach(function(key, value){

                            console.log(key["postName"]);
                            newPosts += "<section><form><input type='hidden' name='postID' class='postID' value='" + key["postID"] + "'>";
                            newPosts += "<div><div><img></div>";
                            newPosts += "<div><p>" + key["postName"] + "</p></div></div>";
                            newPosts += "<div><p>" + key["postContent"] + "</p></div>";
                            newPosts += "<div><button id='" + key["postID"] + "' class='like-button' onClick='like(this)' type='button'>Like</button><button onClick='viewComments(this)' class='view-comments-button' type='button'>View Comments</button></div></form></section>";
                        });
                        document.getElementById('body').innerHTML += newPosts;
                    }
                };
                // If the user is looking at someone elses page
                xhttp.open("POST", "http://localhost/fallrAPI/posts/<?php echo($_GET["username"]);?>", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("username=" + document.cookie.split(":")[0]);
                

                // This code creates a follow on like button click //

                var followButton = document.getElementById("follow-button");
                
                followButton.addEventListener("click", function(){
                    xhttp = new XMLHttpRequest();
                    
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var data = xhttp.responseText;
                            if(data == 1){
                                followButton.innerHTML = "Unsubscribe";
                            }
                            else
                                followButton.innerHTML = "Subscribe";
                        }
                    };

                xhttp.open("POST", "http://localhost/fallrAPI/subscribe", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("username=" + document.getElementsByName("username")[0].value);
                })
            });
                function like(element){
                    xhttp = new XMLHttpRequest();
                    
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            var data = xhttp.responseText;
                            if(data == 1){
                                element.innerHTML = "Unlike";
                            }
                            else
                                element.innerHTML = "Like";
                        }
                    };

                    xhttp.open("POST", "http://localhost/fallrAPI/like", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("postID=" + element.id);
                };
                
            function viewComments(element) {
                xhttp = new XMLHttpRequest();
                    
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var data = xhttp.responseText;
                        if(data == 1){
                            element.innerHTML = "Unlike";
                        }
                        else
                            element.innerHTML = "Like";
                    }
                };

                xhttp.open("POST", "http://localhost/fallrAPI/likes", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("postID=" + element.id);
            }
        </script>
    </body>
</html>
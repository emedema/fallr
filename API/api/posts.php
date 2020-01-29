<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Post.php';



header('Content-type: application/json');

if(isset($_POST['postName']))
    $postName = $_POST['postName'];

if(isset($_POST['postContent']))
    $postContent = $_POST['postContent'];

if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

if ($postName && $postContent && $token) {
 
    $connection = createConnection();

    $userID = Login::getIDFromToken($token);
    
    $postCreateSuccess = Post::createPost($connection, $userID, $postName, $postContent);
    if(!$postCreateSuccess)
        header("HTTP/1.1 405 Create Account Failure");
    

    $connection->close();
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

?>
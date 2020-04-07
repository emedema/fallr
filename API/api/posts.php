<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Post.php';
require_once '../Account.php';

header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

$username = NULL;
$postID = NULL;
$postName = NULL;
$postContent = NULL;
$token = NULL;
$image = NULL;

if(isset($_GET['username']))
    $username = $_GET['username'];

if(isset($_GET['postID']))
    $postID = $_GET['postID'];

if(isset($_FILES['myfile']))
    $image = $_FILES['myfile'];
    
if(isset($_POST['postName']))
    $postName = $_POST['postName'];

if(isset($_POST['postContent']))
    $postContent = $_POST['postContent'];

if(isset($_POST['loggedIn']))
    $token = $_POST['loggedIn'];

if(isset($_GET['loggedIn']))
    $token = $_GET['loggedIn'];

if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

$connection = createConnection();

/* DELETE THE POST */

if($postID && $token 
    && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // We also have to check to see if owner & logged in // 
        if((Post::getPostOwner($connection, $postID) == Login::getIDFromToken($token)) 
            && (Login::checkToken($connection, $token))) {
            // Try to delete and if fails change HTTP code //
            if(!Post::deletePost($connection, $postID))
                header("HTTP/1.1 405 Delete Post Failure");
        }
    }

/* UPDATE THE POST */

// If we have all of the data to update
else if($postID && $postName && $postContent && $token
    && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // If the user is logged in and is owner of post //
    if((Post::getPostOwner($connection, $postID) == Login::getIDFromToken($token)) 
        && (Login::checkToken($connection, $token))) {
        // Check to see if the post succeeds //
        if(!Post::updatePost($connection, $postID, $postName, $postContent))
            header("HTTP/1.1 405 Update Post Failure");
    }
    
}

/* GET THE POSTS FOR A USER */

// If we have a username we will get the posts for that user //
else if($username) {

    $result = Post::getPosts($connection, $username);
    $feedData = array();
    while($row = $result->fetch_assoc())
        $feedData[] = $row;
    echo(json_encode($feedData));


}

/* CREATE THE POST */

else if ($postName && $postContent && $token) {
 
    $connection = createConnection();

    $loggedIn = Login::checkToken($connection, $token);

    if($loggedIn) {
        $userID = Login::getIDFromToken($token);

        $image = Account::addImageToSystem($image);
    
        $postCreateSuccess = Post::createPost($connection, $userID, $image, $postName, $postContent);
        if(!$postCreateSuccess)
            header("HTTP/1.1 405 Create Post Failure");
    }
    
}

/* GET A SINGLE POST */

else if($postID) {
    $connection = createConnection();

    $result = Post::getPost($connection, $postID);
    $feedData = array();
    while($row = $result->fetch_assoc())
        $feedData[] = $row;
    echo(json_encode($feedData));
    
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

if(isset($connection) && $connection)
    $connection->close();

?>
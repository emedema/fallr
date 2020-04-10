<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Comment.php';

header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

$postID = NULL;
$comment = NULL;
$token = NULL;

if(isset($_POST['postID']))
    $postID = $_POST['postID'];

if(isset($_POST['comment']))
    $comment = $_POST['comment'];

if(isset($_GET['commentID']))
    $commentID = $_GET['commentID'];

if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

if(isset($_POST['loggedIn']))
    $token = $_POST['loggedIn'];

if(isset($_GET['loggedIn']))
    $token = $_GET['loggedIn'];

if(isset($_GET['delete']))
    $delete = $_GET['delete'];

$connection = createConnection();


if ($postID && $token && $comment) {
 
    $loggedIn = Login::checkToken($connection, $token);

    // If we are logged in, we try to create the like
    if($loggedIn){
        $username = Login::getIDFromToken($token);
        $postCreateSuccess = Comment::createComment($connection, $postID, $username, $comment);
        if(!$postCreateSuccess)
            header("HTTP/1.1 405 Create Comment Failure");
    }
    else
        header("HTTP/1.1 407 Login Invalid");
}

// Gets a single comment //
else if($commentID && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $comment = Comment::getComment($connection, $commentID);
    echo(json_encode($comment));
}

else if($commentID && $delete && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $connection = createConnection();

    $loggedIn = Login::checkToken($connection, $token);

    // If we are logged in, we try to create the like //
    if($loggedIn){
        
        $username = Login::getIDFromToken($token);

        if($username == Comment::getCommentOwner($connection, $commentID)) {
            Comment::deleteComment($connection, $commentID);
        }
        else
            header("HTTP/1.1 408 Not Owner");
    }
    else
        header("HTTP/1.1 407 Login Invalid");
}

else
    header("HTTP/1.1 406 Parameters Not Passed");


if(isset($connection) && $connection)
    $connection->close();
?>
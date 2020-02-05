<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Comment.php';

header('Content-type: application/json');

$postID = NULL;
$comment = NULL;
$token = NULL;

if(isset($_POST['postID']))
    $postID = $_POST['postID'];

if(isset($_POST['comment']))
    $comment = $_POST['commment'];

if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

if ($postID && $token && $comment) {
 
    $connection = createConnection();

    $loggedIn = Login::checkToken($connection, $token);

    // If we are logged in, we try to create the like
    if($loggedIn){
        $username = Login::getIDFromToken($token);
        Comment::createComment($connection, $postID, $username, $comment);
        if(!$postCreateSuccess)
            header("HTTP/1.1 405 Create Comment Failure");
    }
    else
        header("HTTP/1.1 406 Login Invalid");
}

else if($postID && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    
    $connection = createConnection();

    $loggedIn = Login::checkToken($connection, $token);

    // If we are logged in, we try to create the like //
    if($loggedIn){
        
        $username = Login::getIDFromToken($token);

        if($username == Comment::getCommentOwner($connection, $commentID)) {
            Comment::deleteComment($connection, $commentID);
        }
        else
            header("HTTP/1.1 406 Login Invalid");
    }
    else
        header("HTTP/1.1 406 Login Invalid");
}

else
    header("HTTP/1.1 406 Parameters Not Passed");


if(isset($connection) && $connection)
    $connection->close();
?>
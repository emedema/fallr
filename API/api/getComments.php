<?php

require_once '../Login.php';
require_once '../connection.php';
require_once '../Comment.php';

$postID = NULL;
$commentID = NULL;

if(isset($_GET['postID']))
    $postID = $_GET['postID'];

if(isset($_GET['commentID']))
    $commentID = $_GET['commentID'];

header('Content-type: application/json');
header("Access-Control-Allow-Origin: *");

if ($postID) {
    $connection = createConnection();
    $comments = Comment::getComments($connection, $postID);
    if(!$comments)
        header("HTTP/1.1 405 Get Comments Failure");
    else {
        $commentData = array();
        while($row = $comments->fetch_assoc())
            $commentData[] = $row;
        echo(json_encode($commentData));
    }
}

else if ($commentID) {
    $connection = createConnection();
    $comment = Comment::getComment($connection, $commentID);
    if(!$comment)
        header("HTTP/1.1 405 Get Single Comment Failure");
    else {
        echo(json_encode($comment));
    }
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

if(isset($connection) && $connection)
    $connection->close();
?>
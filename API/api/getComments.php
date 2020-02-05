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
    $comments = Comment::getComment($connection, $commentID);
    if(!$comments)
        header("HTTP/1.1 405 Get Single Comment Failure");
    else {
        $commentData = array();
        while($row = $comments->fetch_assoc())
            $commentData[] = $row;
        echo(json_encode($commentData));
    }
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

if(isset($connection) && $connection)
    $connection->close();
?>
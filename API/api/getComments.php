<?php

require_once '../Login.php';
require_once '../connection.php';
require_once '../Comment.php';

$postID = NULL;

if(isset($_GET['postID']))
    $postID = $_GET['postID'];

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
?>
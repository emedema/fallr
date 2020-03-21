<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Like.php';

header("Access-Control-Allow-Origin: https://fallr.ca");
header('Content-type: application/json');

if(isset($_POST['postID']))
    $postID = $_POST['postID'];

if(isset($_POST['loggedIn']))
    $token = $_POST['loggedIn'];

if(isset($_GET['loggedIn']))
    $token = $_GET['loggedIn'];

if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

if(isset($_GET['postID']))
    $postID = $_GET['postID'];

if ($postID && $token && $_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $connection = createConnection();

    $loggedIn = Login::checkToken($connection, $token);

    // If we are logged in, we try to create the like
    if($loggedIn){
        $username = Login::getIDFromToken($token);
        $postCreateSuccess = Like::createLike($connection, $postID, $username);
        if(!$postCreateSuccess){
            Like::removeLike($connection, $postID, $username);
            echo(0);
        }
        else
            echo(1);
    }
    else
        header("HTTP/1.1 406 Login Invalid");
    }

else if($postID && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $connection = createConnection();

    $likesForPost = Like::getLikesForPost($connection, $postID);
    /* Loop through the data and send it to the user */
    if($likesForPost) {
        $postGetData = array();
        while($row = $likesForPost->fetch_assoc())
            $postGetData[] = $row;
        echo(json_encode($postGetData));
    }
    else
        header("HTTP/1.1 406 Parameters Not Passed");
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

if(isset($connection) && $connection)
    $connection->close();

?>
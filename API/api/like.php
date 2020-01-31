<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Like.php';


header('Content-type: application/json');

if(isset($_POST['postID']))
    $postID = $_POST['postID'];

if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

if ($postID && $token) {
 
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
else
    header("HTTP/1.1 406 Parameters Not Passed");

if(isset($connection) && $connection)
    $connection->close();

?>
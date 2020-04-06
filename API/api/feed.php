<?php

require_once '../connection.php';
require_once '../Account.php';
require_once '../Login.php';
require_once '../Post.php';



header('Content-type: application/json');

if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

if(isset($_POST['loggedIn']))
    $token = $_POST['loggedIn'];

if(isset($_GET['loggedIn']))
    $token = $_GET['loggedIn'];

// Just getting the logged in users data //
if ($token) {
 
    $connection = createConnection();
    
    if(Login::checkToken($connection, $token))
        $username = Login::getIDFromToken($token);
    
    else
        header("HTTP/1.1 407 Login Invalid");

    if($username) {
        $result = Post::getFeed($connection, $username);
        $feedData = array();
        while($row = $result->fetch_assoc())
            $feedData[] = $row;
        echo(json_encode($feedData));
    }
    else
        header("HTTP/1.1 405 Get Feed Failure");

    $connection->close();
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

if(isset($connection) && $connection)
    $connection->close();
?>
<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Subscription.php';

header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

if(isset($_POST['username']))
    $username = $_POST['username'];

if(isset($_POST['loggedIn']))
    $token = $_POST['loggedIn'];

if(isset($_GET['loggedIn']))
    $token = $_GET['loggedIn'];

if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

if(isset($_POST['loggedIn']))
    $token = $_POST['loggedIn'];

if(isset($_GET["username"]))
    $username = $_GET["username"];
    
if ($username && $token && $_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $connection = createConnection();

    /* If the user is logged in */
    if(Login::checkToken($connection, $token)) {
        $myUsername = Login::getIDFromToken($token);

        $success = Subscription::createSubscription($connection, $myUsername, $username);
        
        if(!$success){
            Subscription::removeSubscription($connection, $myUsername, $username);
            echo(0);
        }
        else
            echo(1);
        
    }
    else
        header("HTTP/1.1 406 Login Invalid");

    $connection->close();
}

else if($username && $_SERVER['REQUEST_METHOD'] === 'GET') {
    
    $connection = createConnection();

    $subscriptions = Subscription::getSubscriptions($connection, $username);
        
    /* Loop through the data and send it to the user */
    if($subscriptions) {
        $subscriptionsData = array();
        while($row = $subscriptions->fetch_assoc())
            $subscriptionsData[] = $row;
        echo(json_encode($subscriptionsData));
    }
    else
        header("HTTP/1.1 406 Parameters Not Passed");


    $connection->close();
}

else
    header("HTTP/1.1 406 Parameters Not Passed");

?>
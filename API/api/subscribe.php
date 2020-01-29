<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Subscription.php';

header('Content-type: application/json');

if(isset($_POST['username']))
    $username = $_POST['username'];

if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];
    
if ($username && $token) {
 
    $connection = createConnection();

    $myUsername = Login::getIDFromToken($token);

    $success = Subscription::createSubscription($connection, $myUsername, $username);
    
    if(!$success)
        $success = Subscription::removeSubscription($connection, $myUsername, $username);
    
    $connection->close();
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

?>
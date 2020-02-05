<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Account.php';


header('Content-type: application/json');

if(isset($_GET['username']))
    $username = $_GET['username'];
    
if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

// If we are getting our own information we must be logged in as us //
if($username && $token && ($username == Login::getIDFromToken($token))) {
    $connection = createConnection();

    $loggedIn = Login::checkToken($connection, $token);

    // If the user is logged in and the username matches the token username //
    if($loggedIn) {
        $result = Account::getPersonalUserInfo($connection, $username);
        $feedData = array();
        while($row = $result->fetch_assoc())
            $feedData[] = $row;
        echo(json_encode($feedData));
    }
    
    else
        header("HTTP/1.1 405 Bad Login");

}

else if($username) {
    $connection = createConnection();

    $result = Account::getGeneralUserInfo($connection, $username);
    $feedData = array();
    while($row = $result->fetch_assoc())
        $feedData[] = $row;
    echo(json_encode($feedData));

}

else
    header("HTTP/1.1 406 Parameters Not Passed");

$connection->close();

?>
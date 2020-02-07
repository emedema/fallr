<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Account.php';


header('Content-type: application/json');

if(isset($_POST['username']))
    $username = $_POST['username'];

if(isset($_POST['firstName']))
    $username = $_POST['firstName'];

if(isset($_GET['lastName']))
    $username = $_GET['lastName'];
    
if(isset($_GET['email']))
    $username = $_GET['email'];

if(isset($_GET['image']))
    $username = $_GET['image'];
    
if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

// Updating user information //
if($username && $token && 
    ($username == Login::getIDFromToken($token)) 
    && ($_SERVER['REQUEST_METHOD'] === 'POST')) {
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
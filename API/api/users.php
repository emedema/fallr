<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Account.php';

header("Access-Control-Allow-Origin: https://fallr.ca");
header('Content-type: application/json');

if(isset($_POST['username']))
    $username = $_POST['username'];

if(isset($_POST['firstName']))
    $firstName = $_POST['firstName'];

if(isset($_GET['username']))
    $username = $_GET['username'];

if(isset($_GET['firstName']))
    $firstName = $_GET['firstName'];

if(isset($_POST['lastName']))
    $lastName = $_POST['lastName'];
    
if(isset($_POST['email']))
    $email = $_POST['email'];

if(isset($_FILES['image']))
    $image = $_FILES['image'];

if(isset($_POST['loggedIn']))
    $token = $_POST['loggedIn'];

if(isset($_GET['loggedIn']))
    $token = $_GET['loggedIn'];
    
if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

if(isset($_GET['updateUser']))
    $updateUser = true;

if(isset($_GET['deactivateUser']))
    $deactivateUser = true;

$connection = createConnection();


// If the user is logged in, and is an admin, and is requesting a PUT, and it is a deactivate user
if($token && Account::isAdmin($connection, Login::getIDFromToken($token)) && 
    ($_SERVER['REQUEST_METHOD'] === 'POST') && $deactivateUser) {
    
    $loggedIn = Login::checkToken($connection, $token);

    // If the user is logged in, we allow them to update the status
    if($loggedIn) {
        // If they are already active, deactivate
        if(Account::isActive($connection, $username))
            Account::deactivateAccount($connection, $username);
        // Else we need to activate the account //
        else
            Account::activateAccount($connection, $username);
    }

}

// Updating user information //
else if($updateUser && $username && $token && 
    ($username == Login::getIDFromToken($token)) 
    && ($_SERVER['REQUEST_METHOD'] === 'POST')) {

    $loggedIn = Login::checkToken($connection, $token);

    // If the user is logged in and the username matches the token username //
    if($loggedIn) {
        $result = Account::updateUserInfo($connection, $username, $email, $firstName, $lastName);
    }
    
    else
        header("HTTP/1.1 405 Bad Login");

}

// If we are getting our own information we must be logged in as us //
else if($username && $token && ($username == Login::getIDFromToken($token))) {
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
else if($token && ($username == Login::getIDFromToken($token)) 
        && ($_SERVER['REQUEST_METHOD'] === 'PUT') && !$deactivateUser) {
    
    $loggedIn = Login::checkToken($connection, $token);

    if($loggedIn) {
        Account::updateUserInfo($connection, $username, $email, $firstName, $lastName, $image);
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
<?php

require_once '../Login.php';
require_once '../connection.php';
require_once '../Account.php';

// Allows the server to send data to other site (We are only allowing the current domain) //
header("Access-Control-Allow-Origin: https://fallr.ca"); 

// Sets the type to be JSON/Javascript
header("Content-Type:application/javascript");

$connection = createConnection();

// If the password is set we can simply log the person in //
if (isset($_POST['username']) && isset($_POST['password']) && ($_SERVER['REQUEST_METHOD'] === 'POST')) {

    $password = $_POST['password'];
    $username = $_POST['username'];
    
    /* Checks to see if the user and pass are correct */
    $loginResult = Login::loginUser($connection, $username, $password);
    
    if($loginResult) {
        /* Creates a random token to auth the user for the session */
        setcookie('loggedIn', Login::createToken($connection, $username));
        print(json_encode(array("loggedIn"=> Login::createToken($connection, $username))));  
    }
    else
        header("HTTP/1.1 407 Login Invalid");
}

// Else we are going to send an update password request to an email //
else if(($_SERVER['REQUEST_METHOD'] === 'POST') && isset( $_POST['email'])) {
    $email = $_POST['email'];
    Account::updatePasswordEmail($connection, $email);
}

else
    header("HTTP/1.1 406 Parameters Not Passed");

?>
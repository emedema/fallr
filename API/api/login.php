<?php

require_once '../Login.php';
require_once '../connection.php';

// Allows the server to send data to other site //
header("Access-Control-Allow-Origin: *"); 

// Sets the type to be JSON/Javascript
header("Content-Type:application/javascript");

if (isset($_POST['username']) && isset($_POST['password'])) {

    $password = $_POST['password'];
    $username = $_POST['username'];
    
    $connection = createConnection();

    /* Checks to see if the user and pass are correct */
    $loginResult = Login::loginUser($connection, $username, $password);
    
    if($loginResult) {
        /* Creates a random token to auth the user for the session */
        echo(json_encode(["LoggedIn" => Login::createToken($connection, $username)]));
    }
    else
        header("HTTP/1.1 401 Login Failure");
}
else
    header("HTTP/1.1 404 Parameters Not Passed");

?>
<?php

require_once '../Login.php';
require_once '../connection.php';


if (($username = isset($_POST['username'])) && ($password = isset($_POST['password']))) {
 
    $connection = createConnection();

    /* Checks to see if the user and pass are correct */
    $loginResult = Login::loginUser($connection, $username, $password);
    
    if($loginResult) {
        /* Creates a random token to auth the user for the session */
        // Sets the type to be JSON
        header("Content-Type:application/json");
        echo(Login::createToken($connection, $userid));
    }
    else
        header("HTTP/1.1 405 Login Failure");
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

?>
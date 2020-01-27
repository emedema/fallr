<?php

require_once '../connection.php';
require_once '../Account.php';
require_once '../Login.php';


header('Content-type: application/json');


if (
    ($username = isset($_POST['username'])) 
    && ($password = isset($_POST['password']))
    && ($firstName = isset($_POST['firstName']))
    && ($lastName = isset($_POST['lastName']))
    && ($email = isset($_POST['email']))
    && ($image = isset($_POST['image']))) {
 
    $connection = createConnection();

    $userid = Account::createAccount($connection, $username, $password, $firstName, $lastName, $email, $image);
    
    if($userid > 0) {
        /* Creates a random token to auth the user for the session */
        // Sets the type to be JSON
        echo(Login::createToken($connection, $userid));
    }
    else
        header("HTTP/1.1 405 Create Account Failure");

    $connection->close();
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

?>
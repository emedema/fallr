<?php

require_once '../connection.php';
require_once '../Account.php';
require_once '../Login.php';


header('Content-type: application/json');

$username = NULL;
$password = NULL;
$firstName = NULL;
$lastName = NULL;
$email = NULL;
$image = NULL;

if(isset($_POST['username']))
    $username = $_POST['username'];

if(isset($_POST['password']))
    $password = $_POST['password'];

if(isset($_POST['firstName']))
    $firstName = $_POST['firstName'];

if(isset($_POST['lastName']))
    $lastName = $_POST['lastName'];

if(isset($_POST['email']))
    $email = $_POST['email'];

if(isset($_FILES['image']))
    $image = $_FILES['image'];


if ($username && $password && $email) {
 
    $connection = createConnection();

    $createSuccess = Account::createAccount($connection, $username, $password, $firstName, $lastName, $email, $image);
    
    if($createSuccess) {
        /* Creates a random token to auth the user for the session */
        setcookie('loggedIn', Login::createToken($connection, $username));
    }
    else
        header("HTTP/1.1 405 Create Account Failure");

}
else
    header("HTTP/1.1 406 Parameters Not Passed");


if(isset($connection) && $connection)
    $connection->close();

?>
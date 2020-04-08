<?php

require_once '../connection.php';
require_once '../Login.php';
require_once '../Account.php';

header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

if(isset($_GET['username']))
    $username = $_GET['username'];

if(isset($_POST['loggedIn']))
    $token = $_POST['loggedIn'];

if(isset($_COOKIE['loggedIn']))
    $token = $_COOKIE['loggedIn'];

$connection = createConnection();

// Else if we are trying to figure out if the person is an admin //
if($username) {
    $result = Account::isAdmin($connection, $username);
    print($result);
}

else
    header("HTTP/1.1 406 Parameters Not Passed");

$connection->close();

?>
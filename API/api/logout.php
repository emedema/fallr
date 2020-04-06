<?php

require_once '../Login.php';
require_once '../connection.php';
require_once '../Account.php';

// Allows the server to send data to other site (We are only allowing the current domain) //
header("Access-Control-Allow-Origin: *"); 

// Sets the type to be JSON/Javascript
header("Content-Type:application/javascript");

$connection = createConnection();

if(isset($_POST['loggedIn']))
    $loggedIn = $_POST['loggedIn'];

if(isset($_COOKIE['loggedIn']))
    $loggedIn = $_COOKIE['loggedIn'];

// If the password is set we can simply log the person out //
if (isset($loggedIn) && ($_SERVER['REQUEST_METHOD'] === 'POST'))
    Login::removeSessionToken($connection, $loggedIn);

else
    header("HTTP/1.1 406 Parameters Not Passed");

?>
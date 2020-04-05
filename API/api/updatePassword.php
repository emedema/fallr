<?php

require_once '../connection.php';
require_once '../Account.php';

header("Access-Control-Allow-Origin: https://fallr.ca");

if(isset($_POST['password']))
    $password = $_POST['password'];

if(isset($_POST['uniqueID']))
    $url = $_POST['uniqueID'];

if($password && $url) {
    $connection = createConnection();
    $password = Account::createHashedPassword($password);
    Account::updatePassword($connection, $url, $password);
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

if(isset($connection) && $connection)
    $connection->close();
?>
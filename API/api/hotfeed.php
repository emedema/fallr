<?php

require_once '../connection.php';
require_once '../Account.php';
require_once '../Login.php';
require_once '../Post.php';



header('Content-type: application/json');

$connection = createConnection();


    $result = Post::getHot($connection);
    $hotFeedData = array();
    while($row = $result->fetch_assoc())
        $hotFeedData[] = $row;
    echo(json_encode($hotFeedData));

$connection->close();

?>
<?php

require_once '../connection.php';
require_once '../Post.php';

header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');

if(isset($_GET['type']))
    $type = $_GET['type'];

$connection = createConnection();

if($type === 'hour')
    $postQuery = Post::getPostsTimePeriod($connection, 1);
else if($type === 'day')
    $postQuery = Post::getPostsTimePeriod($connection, 24);
else if($type === 'week')
    $postQuery = Post::getPostsTimePeriod($connection, 168);
// We are going to assume that a month is 30 days cause I don't wanna do that math // 
else if($type === 'month')
    $postQuery = Post::getPostsTimePeriod($connection, 720);

if($postQuery != null){
    $posts = array();
    while($row = $postQuery->fetch_assoc()){
        $posts[] = $row;
    }
    echo(json_encode($posts));
}
else
    header("HTTP/1.1 406 Parameters Not Passed");

?>
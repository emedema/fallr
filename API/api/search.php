<?php

require_once '../connection.php';
require_once '../Account.php';
require_once '../Post.php';

header("Access-Control-Allow-Origin: https://fallr.ca");
header('Content-type: application/json');

if(isset($_GET['username']))
    $username = $_GET['username'];

if(isset($_GET['email']))
    $email = $_GET['email'];

if(isset($_GET['post']))
    $post = $_GET['post'];

if(isset($_GET['type']))
    $type = $_GET['type'];

$connection = createConnection();


// If you specify a username, search based on a username //
if($type === 'username' && $username) {
    $userQuery = Account::searchUser($connection, $username);
    $users = array();
    while($row = $userQuery->fetch_assoc()){
        $users[] = $row;
    }
    echo(json_encode($users));
}

else if($type === 'email' && $email) {
    $userQuery = Account::searchEmail($connection, $email);
    $users = array();
    while($row = $userQuery->fetch_assoc()){
        $users[] = $row;
    }
    echo(json_encode($users));
}

else if($type === 'post' && $post) {
    $userQuery = Account::searchPost($connection, $post);
    $users = array();
    while($row = $userQuery->fetch_assoc()){
        $users[] = $row;
    }
    echo(json_encode($users));
}

else if($type === 'allPosts' && $post) {
    $postQuery = Post::searchPosts($connection, $post);
    $posts = array();
    while($row = $postQuery->fetch_assoc()){
        $posts[] = $row;
    }
    echo(json_encode($posts));
}

else
    header("HTTP/1.1 406 Parameters Not Passed");

$connection->close();

?>
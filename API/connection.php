<?php
    require_once 'credentials.php';

    function createConnection() {
    /**
     * Function that creates a connection to a database and check to be sure
     * that it has succeeded.
     */

        list ($username, $password, $database, $server) = getDBLogin();
        // Create Connection (because OOP is better)
        $connection = new mysqli($server, $username, $password, $database);
        
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        
        return $connection;

    };

?>
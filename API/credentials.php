<?php
    function getDBLogin(){

        /** 
         * Description: A function that returns an array of the login information.
         * Takes: Nothing
         * Returns: Array of username, password, database, and server
        */

		$username = "fallr";
		$password = "072e47704030abe4a7043329e3da4001";
		$database = "fallr";
		$server = "localhost";

		return array($username, $password, $database, $server);
    }
    
    function getSecretKey() {
        return "9q8he9q8be9q8e69v87q3q7tbc97r238t92qc7r3g897q23t9omawonxd8bq7t93rc";
    }

    function getAWSKey() {
        return [
            'key'    => '',
            'secret' => ''
        ];
    }
?>
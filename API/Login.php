<?php
class Login {

    public function loginUser($connection, $username, $password) {
        /* Function that checks to see if a user exists and the passwords match */

        /* Gets the hashed password from the entered user */
        $query = $connection->prepare("SELECT password FROM Users WHERE username = ?");
        $query->bind_param("s", $username); 
        $query->execute();
        $serverPassword = $query->get_result()->fetch_assoc()["password"];
        
        /* Checks to see if the hashed passwords match */
        if (password_verify($password, $serverPassword)) {
            return TRUE;
        }
        else 
            return FALSE;

    }

    public function storeToken($connection, $user, $token) {
        /* Function to store the value for the token for a user in the database 
            Returns the id for the insertion.
        */
        $query = $connection->prepare("INSERT INTO Tokens (username, token) VALUES (?, ?) ");
        $query->bind_param("ss", $user, $token);
        $query->execute();
        return $query->insert_id;
    }

    public function fetchToken($connection, $user, $tokenId){
        /* Function to get the token and id for some user from the database */

        $query = $connection->prepare("SELECT token FROM Tokens WHERE username=? and tokenID=?");
        $query->bind_param("si", $user, $tokenId);
        $query->execute();
        $token = $query->get_result()->fetch_assoc();
        return $token["token"];
    }

    public function removeToken($connection, $username, $tokenId) {
        /* Function to remove the token for some user */
        
        $query = $connection->prepare("DELETE from Tokens WHERE username=? and tokenID=?");
        $query->bind_param("si", $username, $tokenId);
        $query->execute();
    }

    public function createToken($connection, $user) {

        $SECRET_KEY = getSecretKey();

        /* Creates a random 255 length token */
        $token = bin2hex(random_bytes(255));
        /* Stores the token in the database */
        $tokenID = self::storeToken($connection, $user, $token);

        $cookie = $user . ':'. $tokenID .':' . $token;
        $hash = hash_hmac('sha256', $cookie, $SECRET_KEY);
        $cookie .= ':' . $hash;
        return $cookie;
    }

    public function checkToken($connection, $cookie) {
        /* Function that checks to see if the current user is logged in based on their cookie */

        $SECRET_KEY = getSecretKey();

        /* This checks to see if the cookie exists */
        if ($cookie) {
            list ($user, $tokenID, $token, $hash) = explode(':', $cookie);
            
            /* This checks to see if the hash matches */
            if (!hash_equals(hash_hmac('sha256', $user . ':'.$tokenID .':' . $token, $SECRET_KEY), $hash)) {
                return false;
            }
            /* Gets the token and checks if the user's token matches that of the user */
            $usertoken = self::fetchToken($connection, $user, $tokenID);
            if (hash_equals($usertoken, $token)) {
                return true;
            }
        }
    }

    public function getIDFromToken($cookie) {
        $username = explode(':', $cookie)[0]; 
        return $username;
    }

    public function removeSessionToken($connection, $token){
        $cookie = $_COOKIE["loggedIn"];
        // Gets the ID and username from the token //
        if($token) {
            $username = explode(":", $token)[0];
            $tokenID = explode(":", $token)[1];  
        }
        // Else get it from the session //
        else {
            $username = explode(":", $cookie)[0];
            $tokenID = explode(":", $cookie)[1];   
        }

        setcookie('loggedIn', NULL);
        self::removeToken($connection, $username, $tokenID);
    }
}
?>
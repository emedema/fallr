<?php
class Subscription {
	
	public function createSubscription($connection, $yourUsername, $username) {
	
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("INSERT INTO Subscriptions (subscribedUsername, username) VALUES (?, ?)");
		/* Passes the values into the query */
		$query->bind_param("ss", $yourUsername, $username);
        
		/* Returns success */
		return $query->execute();
    }
    
    public function removeSubscription($connection, $yourUsername, $username) {
        /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("DELETE FROM Subscriptions WHERE subscribedUsername = ? AND username = ?");		
        $query->bind_param("ss", $yourUsername, $username);
        
		/* Returns success */
		return $query->execute();
    }
}

?>
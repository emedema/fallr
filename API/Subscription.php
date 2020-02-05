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
	
	public function getSubscriptions($connection, $yourUsername) {
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT * FROM Subscriptions WHERE subscribedUsername = ?");
		/* Passes the values into the query */
		$query->bind_param("s", $yourUsername);
        $query->execute();
		
		/* Returns success */
		return $query->get_result();
	}

	public function isSubscribed($connection, $yourUsername, $theirUsername){
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT EXISTS(SELECT * from Subscriptions WHERE subscribedUsername = ? AND username = ?)");
		/* Passes the values into the query */
		$query->bind_param("ss", $yourUsername, $theirUsername);
        
		/* Returns existance variable */
		return $query->execute()->get_result()->fetch_assoc()[0];
	}
}

?>
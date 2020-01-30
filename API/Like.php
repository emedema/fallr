<?php
class Like {
	
	public function createLike($connection, $postID, $username) {
	
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("INSERT INTO Likes (username, postID) VALUES (?, ?)");
		/* Passes the values into the query */
		$query->bind_param("ss", $username, $postID);
        
		/* Returns success */
		return $query->execute();
    }
    
    public function removeLike($connection, $postID, $username) {
        /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("DELETE FROM Likes WHERE username = ? AND postID = ?");		
		$query->bind_param("ss", $username, $postID);
        
		/* Returns success */
		return $query->execute();
    }
}

?>
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
	
	public function getLikesForPost($connection, $postID) {
        /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT * FROM Likes WHERE postID = ?");		
		$query->bind_param("i", $postID);
		
		$query->execute();
		/* Returns values */
		return $query->get_result();
    }
}

?>
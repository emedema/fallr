<?php
class Comment {
	
	public function createComment($connection, $postID, $username, $comment) {
	
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("INSERT INTO Comments (username, postID, comment) VALUES (?, ?, ?)");
		/* Passes the values into the query */
		$query->bind_param("ss", $username, $postID, $comment);
        
		/* Returns success */
		return $query->execute();
    }
    
    public function removeComment($connection, $postID, $username) {
        /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("DELETE FROM Comments WHERE username = ? AND postID = ?");		
		$query->bind_param("ss", $username, $postID);
        
		/* Returns success */
		return $query->execute();
    }
}

?>
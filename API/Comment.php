<?php
class Comment {
	
	public function createComment($connection, $postID, $username, $comment) {
	
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("INSERT INTO Comments (username, postID, comment) VALUES (?, ?, ?)");
		/* Passes the values into the query */
		$query->bind_param("sis", $username, $postID, $comment);
        
		/* Returns success */
		return $query->execute();
    }
    
    public function removeComment($connection, $postID, $username) {
        /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("DELETE FROM Comments WHERE username = ? AND postID = ?");		
		$query->bind_param("si", $username, $postID);
        
		/* Returns success */
		return $query->execute();
	}
	
	public function getComments($connection, $postID) {
        /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT commentID, username, postID, comment FROM Comments WHERE postID = ?");		
		$query->bind_param("i", $postID);
        
		/* Returns success */
		$query->execute();
		
		return $query->get_result();
	}

	public function getComment($connection, $commentID) {
        /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT commentID, username, postID, comment FROM Comments WHERE commentID = ?");		
		$query->bind_param("i", $commentID);
        
		/* Returns success */
		$query->execute();
		
		return $query->get_result();
	}

	public function deleteComment($connection, $commentID) {
        /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("DELETE FROM Comments WHERE commentID = ?");		
		$query->bind_param("i", $commentID);
        
		/* Returns success */
		return $query->execute();
	}

	public function getCommentOwner($connection, $commentID) {
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT username FROM Comments WHERE commentID = ?");		
		$query->bind_param("i", $commentID);
		$query->execute();
		/* Returns success */
		return $query->get_result();
	}
}

?>
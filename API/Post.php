<?php
class Post {
	
	public function createPost($connection, $username, $postName, $postContent) {
	
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("INSERT INTO Posts (username, postName, postContent) VALUES (?, ?, ?)");
		/* Passes the values into the query */
		$query->bind_param("sss", $username, $postName, $postContent);
		
        $query->execute();
        
		/* Returns inserted_id */
		return $query->insert_id;
    }
    
    public function getHot($connection) {
        /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT p.postID, p.username, postName, postContent, count(l.username) likes FROM Posts p, Likes l WHERE p.postID = l.postID GROUP BY p.postID ORDER BY likes");		
        $query->execute();
        
		/* Returns inserted_id */
		return $query->get_result();
    }

    public function getFeed($connection, $username) {
         /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT s.subscribedUsername, s.username, p.postID, p.username, 
                                        postName, postContent FROM Posts p, Subscriptions s 
                                        WHERE s.subscribedUsername = (?)
                                        AND s.username = p.username");		

        $query->bind_param("s", $username);

        $query->execute();
        
		/* Returns inserted_id */
		return $query->get_result();
    }
}

?>
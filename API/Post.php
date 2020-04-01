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

    public function updatePost($connection, $postID, $postName, $postContent) {
	
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("UPDATE Posts SET postName = ?, postContent = ? WHERE postID= ?");
		/* Passes the values into the query */
		$query->bind_param("ssi", $postName, $postContent, $postID);
        
		/* Returns Success */
		return $query->execute();
    }

    public function deletePost($connection, $postID) {
	
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("DELETE FROM Posts WHERE postID = ?");
		/* Passes the values into the query */
		$query->bind_param("i", $postID);
        
		/* Returns success */
		return $query->execute();
    }
    
    public function getHot($connection) {
        /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT p.postID, p.username, postName, postContent, count(l.username) likes FROM Posts p, Likes l WHERE p.postID = l.postID GROUP BY p.postID ORDER BY likes DESC");		
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

    public function getPosts($connection, $username) {
        /* Prepares the function so we can pass in the values from the user */
       $query = $connection->prepare("SELECT postID, username, postName, postContent FROM Posts WHERE username = ?");		

       $query->bind_param("s", $username);

       $query->execute();
       
       /* Returns inserted_id */
       return $query->get_result();
    }

    public function getPost($connection, $postID) {
        /* Prepares the function so we can pass in the values from the user */
       $query = $connection->prepare("SELECT postID, username, postName, postContent FROM Posts WHERE postID = ?");		

       $query->bind_param("i", $postID);

       $query->execute();
       
       /* Returns inserted_id */
       return $query->get_result();
    }

    public function getPostOwner($connection, $postID) {
        /* Prepares the function so we can pass in the values from the user */
        $query = $connection->prepare("SELECT username FROM Posts WHERE postID = ?");		

        $query->bind_param("s", $postID);

        $query->execute();
        
        /* Returns inserted_id */
        return $query->get_result()->fetch_assoc()["username"];
    }

    public function searchPosts($connection, $partial_post) {		

      $query = $connection->prepare("SELECT * FROM Posts WHERE postName LIKE CONCAT('%',?,'%')");	
      
      $query->bind_param("s", $partial_post);
  
      $query->execute();
  
      return $query->get_result();
    }
}

?>
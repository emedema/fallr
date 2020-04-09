<?php
class Post {
	
	function addImageToPost($connection, $postID, $image) {
			
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("UPDATE Posts SET image = ? WHERE postID = ?");

		/* Passes the values into the query */
		if($query != NULL) {
			$query->bind_param("ss", $image, $postID);	
			/* Returns success */
			return $query->execute();
		}
	}

	public function createPost($connection, $username, $image, $postName, $postContent) {
	
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("INSERT INTO Posts (username, postName, image, postContent) VALUES (?, ?, ?, ?)");
		/* Passes the values into the query */
		$query->bind_param("ssss", $username, $postName, $image, $postContent);
		
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
		$query = $connection->prepare("SELECT u.image, p.postID, p.image AS postImage, p.username, postName, postContent, p.created, count(l.username) likes FROM Posts p LEFT JOIN Likes AS l ON l.postID=p.postID LEFT JOIN Users as u ON u.username = p.username GROUP BY p.postID ORDER BY likes DESC");		
        $query->execute();
        
		/* Returns inserted_id */
		return $query->get_result();
    }

    public function getFeed($connection, $username) {
         /* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT u.image, s.username, p.postID, p.username,
      p.image AS postImage, p.postName, p.postContent, p.created, count(l.username) likes 
      FROM Posts p RIGHT JOIN Subscriptions AS s ON s.username = p.username 
      LEFT JOIN Likes AS l ON l.postID=p.postID LEFT JOIN Users as u ON u.username = p.username
      WHERE s.subscribedUsername = (?) GROUP BY p.postID, s.subscribedUsername, s.username ORDER BY p.created DESC");		

        $query->bind_param("s", $username);

        $query->execute();
        
		/* Returns inserted_id */
		return $query->get_result();
    }

    public function getPosts($connection, $username) {
        /* Prepares the function so we can pass in the values from the user */
       $query = $connection->prepare("SELECT u.image, p.postID, p.username, p.postName, p.image AS postImage, p.postContent, p.created, count(l.username) likes FROM Posts p LEFT JOIN Likes AS l ON l.postID=p.postID LEFT JOIN Users as u ON u.username = p.username WHERE p.username = ? GROUP BY p.postID ORDER BY p.created DESC");		

       $query->bind_param("s", $username);

       $query->execute();
       
       /* Returns inserted_id */
       return $query->get_result();
    }

    public function getPost($connection, $postID) {
        /* Prepares the function so we can pass in the values from the user */
       $query = $connection->prepare("SELECT u.image, p.postID, p.username, p.postName, p.image AS postImage, p.postContent, p.created, count(l.username) likes FROM Posts p LEFT JOIN Likes AS l ON l.postID=p.postID LEFT JOIN Users as u ON u.username = p.username WHERE p.postID = ? GROUP BY p.postID");		

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

      $query = $connection->prepare("SELECT u.image, p.postID, p.username, p.postName, p.image AS postImage, p.postContent, p.created, count(l.username) likes FROM  
          Posts p LEFT JOIN Likes AS l ON l.postID=p.postID JOIN Users as u ON u.username = p.username WHERE p.postName LIKE CONCAT('%',?,'%') GROUP BY p.postID ORDER BY p.created DESC");	
      $query->bind_param("s", $partial_post);
  
      $query->execute();
  
      return $query->get_result();
    }

    public function getPostsTimePeriod($connection, $hours){
      // This function gets all posts for a timeperiod (now - hours * n )
      $query = $connection->prepare("SELECT u.image, p.postID, p.username, p.postName, p.image AS postImage, p.postContent, p.created, count(l.username) likes FROM  
      Posts p LEFT JOIN Likes AS l ON l.postID=p.postID LEFT JOIN Users as u ON u.username = p.username WHERE p.created > (CURRENT_TIMESTAMP - INTERVAL ? HOUR) GROUP BY p.postID ORDER BY p.created DESC");	
      $query->bind_param("s", $hours);
  
      $query->execute();
  
      return $query->get_result();
    }
}

?>
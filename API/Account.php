<?php
class Account {
	
	public function createHashedPassword($password){
		$options = ['cost'=>11];
		return password_hash($password, PASSWORD_DEFAULT, $options);
	}

	public function createAccount($connection, $username, $password, $firstName, $lastName, $email, $image) {
		
		/* Creates a hashed password */
		$password = self::createHashedPassword($password);
	
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("INSERT INTO Users (username, password, firstName, lastName, email, image) VALUES (?, ?, ?, ?, ?, ?)");

		/* Passes the values into the query */
		if($query != NULL) {
			$query->bind_param("ssssss", $username, $password, $firstName, $lastName, $email, $image);	
			/* Returns success */
			return $query->execute();
		}
	}

	public function getGeneralUserInfo($connection, $username) {
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT username, image FROM Users WHERE username = ?");		

		$query->bind_param("s", $username);

		$query->execute();
		
		/* Returns inserted_id */
		return $query->get_result();
	}

	public function getPersonalUserInfo($connection, $username) {
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("SELECT username, email, firstName, lastName, image FROM Users WHERE username = ?");		

		$query->bind_param("s", $username);

		$query->execute();
		
		/* Returns inserted_id */
		return $query->get_result();
	}

	public function updateUserInfo($connection, $username) {
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("UPDATE Users SET username = ?, email = ?, firstName = ?, lastName = ?, image = ? WHERE username = ?");		

		$query->bind_param("s", $username);

		$query->execute();
		
		/* Returns inserted_id */
		return $query->get_result();
	}
}

?>
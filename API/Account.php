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

	public function updateUserInfo($connection, $username, $email, $firstName, $lastName, $image=NULL) {

		/* Prepares the function so we can pass in the values from the user */
		if($image != NULL) {
			$query = $connection->prepare("UPDATE Users SET email = ?, firstName = ?, lastName = ?, image = ? WHERE username = ?");		
			$query->bind_param("sssss", $email, $firstName, $lastName, $image, $username);
		}

		// If the user didn't specify an image, this will keep the image from being overwritten //
		else {
			$query = $connection->prepare("UPDATE Users SET email = ?, firstName = ?, lastName = ? WHERE username = ?");		
			$query->bind_param("ssss", $email, $firstName, $lastName, $username);

		}

		$query->execute();
		
		/* Returns inserted_id */
		return $query->get_result();
	}

	public function isAdmin($connection, $username) {
		$query = $connection->prepare("SELECT admin FROM Users WHERE username = ?");
		$query->bind_param("s", $username);
		$query->execute();
		$value = $query->get_result()->fetch_assoc();
		return $value["admin"];
	}

	public function isActive($connection, $username) {
		$query = $connection->prepare("SELECT active FROM Users WHERE username = ?");
		$query->bind_param("s", $username);
		$query->execute();
		$value = $query->get_result()->fetch_assoc();
		return $value["active"];
	}

	public function updatePasswordEmail($connection, $email) {
		/* Prepares the function so we can pass in the values from the user */
		$query = $connection->prepare("INSERT INTO UpdatePassword (email, link) VALUES (?, ?)");		

		$url = uniqid($email, true);

		$query->bind_param("ss", $email, $url);

		$query->execute();
		
		// Sends the email //
		$to = $email;
		$subject = "Reset password on fallr.ca";
		$msg = "Hi there, reset your password here: https://fallr.ca/?uniqueID=" . $url;
		$msg = wordwrap($msg,70);
		$headers = "From: reset@fallr.ca";
		mail($to, $subject, $msg, $headers);

		/* Returns inserted_id */
		return $query->get_result();
	}

	// This function takes in a password reset and allows a user to update password 
	// Requires a pre-hased password //
	public function updatePassword($connection, $url, $password) {
		// This only allows the request if the email was sent within the last 24 hours //
		$query = $connection->prepare("SELECT email FROM UpdatePassword WHERE link=?, and creationDate < (NOW()-INTERVAL 1 DAY)");		

		$query->bind_param("s", $url);

		$query->execute();
		
		// Get the username for the user based on the URL //
		$email = $query->get_result()->fetch_assoc();

		$query = $connection->prepare("UPDATE users SET password = ? WHERE email = ?");		

		$query->bind_param("ss", $password, $email);

		$query->execute();
		
	}

	public function deactivateAccount($connection, $deactivated_user) {
		// Checks to see if the current user is the same as the account to be deactivated //

		$query = $connection->prepare("UPDATE Users SET active = false WHERE username = ?");		
		$query->bind_param("s", $deactivated_user);

		$query->execute();

		return true;
	}

	public function activateAccount($connection, $deactivated_user) {		

		$query = $connection->prepare("UPDATE Users SET active = true WHERE username = ?");		

		$query->bind_param("s", $deactivated_user);

		$query->execute();

		return true;
	}
}

?>
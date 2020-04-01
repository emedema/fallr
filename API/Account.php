<?php

require_once 'vendor/autoload.php';
require_once 'credentials.php';

// The required AWS libs //
use Aws\Ses\SesClient;
use Aws\Exception\AwsException;      
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

		                                                                                                                                                                                                                                                                                                                           
		// If necessary, modify the path in the require statement below to refer to the
		// location of your Composer autoload.php file.
		
		// Create an SesClient. Change the value of the region parameter if you're        
		// using an AWS Region other than US West (Oregon). Change the value of the
		// profile parameter if you want to use a profile in your credentials file
		// other than the default.
		
		if(mysqli_affected_rows($connection) > 0) {
			$SesClient = new SesClient([
				'version' => 'latest',
				'region' => 'us-east-1',
				'credentials' => getAWSKey(),
				]);
				// Replace sender@example.com with your "From" address.
				// This address must be verified with Amazon SES.
				$sender_email = 'reset@mail.fallr.ca';
				// Replace these sample addresses with the addresses of your recipients. If
				// your account is still in the sandbox, these addresses must be verified.
				$recipient_emails = [$email];
				// Specify a configuration set. If you do not want to use a configuration
				// set, comment the following variable, and the
				// 'ConfigurationSetName' => $configuration_set argument below.
				$configuration_set = 'ConfigSet';
				$subject = "Reset password on fallr.ca";
				$plaintext_body = "Hi there, reset your password here: https://fallr.ca/passwordUpdate/?uniqueID=" . $url;
				$html_body =  "Hi there, reset your password here: <a href=https://fallr.ca/passwordUpdate?uniqueID=" . $url . "> https://fallr.ca/passwordUpdate?uniqueID=" . $url . "</a>";
				$char_set = 'UTF-8';
				
				try {    
					$result = $SesClient->sendEmail([
						'Destination' => [
							'ToAddresses' => $recipient_emails,
						],
						'ReplyToAddresses' => [$sender_email],
						'Source' => $sender_email,
						'Message' => [
							'Body' => [
								'Html' => [
									'Charset' => $char_set,
									'Data' => $html_body,
								],
								'Text' => [
									'Charset' => $char_set,
									'Data' => $plaintext_body,
								],
							],
							'Subject' => [
								'Charset' => $char_set,
								'Data' => $subject,
							],
						],
						]);
						$messageId = $result['MessageId'];
						echo("Success");
					} catch (AwsException $e) {
						// output error message if fails
						echo $e->getMessage();
						echo("The email was not sent");
						echo "\n";
					}
		}

		/* Returns inserted_id */
		return $query->get_result();
	}

	// This function takes in a password reset and allows a user to update password 
	// Requires a pre-hased password //
	public function updatePassword($connection, $url, $password) {
		// This only allows the request if the email was sent within the last 24 hours //
		$query = $connection->prepare("SELECT email FROM UpdatePassword WHERE link=? and NOW() < DATE(DATE_ADD(creationDate, INTERVAL 1 DAY))");		
		$query->bind_param("s", $url);

		$query->execute();
		
		// Get the username for the user based on the URL //
		$email = $query->get_result()->fetch_assoc()['email'];

		$query = $connection->prepare("UPDATE Users SET password = ? WHERE email = ?");		

		$query->bind_param("ss", $password, $email);

		$query->execute();

		// Finally if this updates successfully we kill the link //
		if($connection->affected_rows > 0) {
			Account::deleteUpdatePassword($connection, $url);
		}	
	}

	public function deleteUpdatePassword($connection, $url) {
		$query = $connection->prepare("DELETE FROM UpdatePassword WHERE link = ?");		
		$query->bind_param("s", $url);
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

	public function searchUser($connection, $partial_username) {		

		$query = $connection->prepare("SELECT username FROM Users WHERE username LIKE CONCAT('%',?,'%')");	
		
		$query->bind_param("s", $partial_username);

		$query->execute();

		return $query->get_result();
	}

	public function searchPost($connection, $post) {		

		$query = $connection->prepare("SELECT username FROM Posts WHERE postName = ?");	
		
		$query->bind_param("s", $post);

		$query->execute();

		return $query->get_result();
	}

	public function searchEmail($connection, $email) {		

		$query = $connection->prepare("SELECT username FROM Users WHERE email = ?");	
		
		$query->bind_param("s", $email);

		$query->execute();

		return $query->get_result();
	}
}

?>
<?php

// Reguire the DPO database class by Vivek Wicky Aswal
require("Db.class.php");

// Function that checks if a user session is active
function SessionStatus() {
	if (isset($_SESSION['id'])) {
		return true;
	} else {
		return false;
	}
}

// Function that logs out the user by unsetting id and destroying the current session
function Logout() {
	unset( $_SESSION['id'] );
	session_destroy();
}
class User {

	// Create new user and write to database
	public function __construct($posted_username, $posted_password, $posted_formtoken) {

		// Form validation
		$validated = false;
		$message = '';
		if(!isset( $posted_username, $posted_password, $posted_formtoken)) {
			$message = 'Please enter a valid username and password';
		} elseif( $posted_formtoken != $_SESSION['form_token']) {
			$message = 'Invalid form submission';
		} elseif (strlen( $posted_username) > 20 || strlen($posted_username) < 4) {
			$message = 'Incorrect Length for Username';
		} elseif (strlen( $posted_password) > 100 || strlen($posted_password) < 4) {
			$message = 'Incorrect Length for Password';
		} elseif (ctype_alnum($posted_username) != true) {
			$message = "Username must be alpha numeric";
		} else {
			$validated = true;
		}

		// Validation is done

		if ($validated == true) {
			// Sanitize the username and password. Remove prefix and postfix spaces remove code tags and HTML encode special characters
			$username = filter_var(trim($posted_username), FILTER_SANITIZE_STRING);
			$password = filter_var(trim($posted_password), FILTER_SANITIZE_STRING);

		    // Hash the password using BCRYPT and increase difficulty
		    // Set hashing cost (meaning difficulty/strength)
			$options = [
			'cost' => 12,
			];

			// Hash the password and set the plaintext variable to a hashed version
			$password = password_hash($password, PASSWORD_BCRYPT, $options);

	 	   // Open database connection
			global $db;
			$db = new Db($db);

			// Check if username is taken
			$db->bind("username",$username);
			$ifexist = $db->single("SELECT * FROM users WHERE username = :username");
			if ($ifexist) {
				$message = "This username already exists";
			}
			// If username is not taken, start creation query
			else {
				$db->bind("username",$username);
				$db->bind("password",$password);
				$db->query("INSERT INTO users (username,password) VALUES (:username,:password)");
				$message = "Your account was successfully created.";
			}

			// Unset the unique form token
			unset( $_SESSION['form_token'] );

			echo $message;
		}
	}
}

// Session class used to log in
class Session {
	public function __construct($login_username,$login_password) {

		// Validate input
		$validated = false;
		$message = '';

		// Lazy validation, checking purely length. We sanitize later to prevent abuse.
		if( !isset( $login_username, $login_password) || !(strlen($login_username) < 20) || !(strlen($login_password) < 100)) {
			$message = 'Please enter a valid username and password';
		} else {
			$validated = true;
		}

		// Validation complete
		if ($validated) {

			// Sanitize the username and password. Remove prefix and postfix spaces remove code tags and HTML encode special characters
			$login_username = filter_var(trim($login_username), FILTER_SANITIZE_STRING);
			$login_password = filter_var(trim($login_password), FILTER_SANITIZE_STRING);

    		// Open database connection
			global $db;
			$db = new Db($db);

			// Check if username exists in the database
			$db->bind("username",$login_username);
			$ifexist = $db->single("SELECT * FROM users WHERE username = :username");
			if (!$ifexist) {
				$message = "This user does not exist!";
			}

			// If the user exists, start password verification
			else {
				$db->bind("username",$login_username);

				// Query database for stored password hash
				$password_hash = $db->single("SELECT password FROM users WHERE username = :username");

				// Let PHP verify the hash and get user ID if password is correct
				if (password_verify($login_password, $password_hash)) {
					$db->bind("username",$login_username);
					$logged_in_id = $db->single("SELECT id FROM users WHERE username = :username");
				} else {
					$message = "Wrong password";
				}
			}

			// Return error message if the above didn't result in a login
			if(!isset($logged_in_id)) {
				$message = $message . ' Login Failed';
			}

			// Set the user ID into the $_SESSION array and set success message
			else {
				$_SESSION['id'] = $logged_in_id;
				$this->logged_in_id = $logged_in_id;
				$message = 'Login succeeded';
			}
		}

		echo $message;
	}

}

?>
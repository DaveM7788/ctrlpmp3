<?php
class Account {
	private $errorArray = array();
	private $con;

	public function __construct($con) {
		$this->con = $con;
	}

	public function login($un, $pw) {
		//$pw = md5($pw); // !!!!!!!!!!!!!NOT GOOD!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		$pw = password_hash($pw, PASSWORD_BCRYPT);
		$query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");
		if (mysqli_num_rows($query) == 1) {
			return true;
		}
		else {
			array_push($this->errorArray, Constants::$loginFailed);
			return false;
		}
	}

	public function register($un, $fn, $ln, $em, $em2, $pw, $pw2) {
		$this->validateUsername($un);
		$this->validateFirstName($fn);
		$this->validateLastName($ln);
		$this->validateEmails($em, $em2);
		$this->validatePasswords($pw, $pw2);

		if (empty($this->errorArray)) {
			// Insert into db
			return $this->insertUserDetials($un, $fn, $ln, $em, $pw);
		}
		else {
			return false;
		}
	}

	public function getError($error) {
		if (!in_array($error, $this->errorArray)) {
			$error = "";
		}
		return "<span class='errorMessage'>$error</span>";
	}

	private function insertUserDetials($un, $fn, $ln, $em, $pw) {
		//$encrypt = md5($pw);  // !!!!!! WE NEED TO CHANGE THIS 
		$encrypt = password_hash($pw, PASSWORD_BCRYPT);
		$profilePic = "assets/images/profile-pics/productivityICOOn.ico";
		$date = date("Y-m-d");
		// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! NEED PREPARED STMTS
		$result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$fn', '$ln', '$em', '$encrypt', '$date', '$profilePic')");
		return $result;
	}

	private function validateUsername($un) {
		// check correct username length
		if (strlen($un) > 25 || strlen($un) < 5) {
			array_push($this->errorArray, Constants::$usernameCharacters);
			return;
		}

		// check if username already exists
		$checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
		if (mysqli_num_rows($checkUsernameQuery) != 0) {
			array_push($this->errorArray, Constants::$usernameTaken);
			return;
		}
	} 

	private function validateFirstName($fn) {
		if (strlen($fn) > 25 || strlen($fn) < 2) {
			array_push($this->errorArray, Constants::$firstNameCharacters);
			return;
		}
	}

	private function validateLastName($ln) {
		if (strlen($ln) > 25 || strlen($ln) < 2) {
			array_push($this->errorArray, Constants::$lastNameCharacters);
			return;
		}
	}

	private function validateEmails($em, $em2) {
		if ($em != $em2) {
			array_push($this->errorArray, Constants::$emailsDoNotMatch);
			return;
		}

		if (!filter_var($em, FILTER_VALIDATE_EMAIL)) {
			array_push($this->errorArray, Constants::$emailInvalid);
			return;
		}

		// check that email doesn't already exist in db
		$checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
		if (mysqli_num_rows($checkEmailQuery) != 0) {
			array_push($this->errorArray, Constants::$emailTaken);
			return;
		}
	}

	private function validatePasswords($pw, $pw2) {
		if ($pw != $pw2) {
			array_push($this->errorArray, Constants::$passwordsDoNotMatch);
			return;
		}

		if (strlen($pw) > 40 || strlen($pw) < 6) {
			array_push($this->errorArray, Constants::$passwordCharacters);
			return;
		}
	}
}
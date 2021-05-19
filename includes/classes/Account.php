<?php
class Account {
	private $errorArray = array();
	private $con;
	
	public function __construct($con) {
		$this->con = $con;
	}
	
	public function login($un, $pw) {
		$query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' LIMIT 1");
		if (mysqli_num_rows($query) == 1) {
			while ($row = mysqli_fetch_row($query)) {
				if ($row[5] == 1) {
					if (password_verify($pw, $row[3])) {
						return true;
					} else {
						array_push($this->errorArray, Constants::$loginFailed);
						return false;
					}
				} else {
					array_push($this->errorArray, Constants::$doNotHaveAcc);
					return false;
				}
			}
		} else {
			array_push($this->errorArray, Constants::$loginFailed);
			return false;
		}

	}

	public function register($un, $em, $em2, $pw, $pw2) {
		$this->validateUsername($un);
		$this->validateEmails($em, $em2);
		$this->validatePasswords($pw, $pw2);

		if (empty($this->errorArray)) {
			return $this->insertUserDetials($un, $em, $pw);
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

	private function insertUserDetials($un, $em, $pw) {
		$hashed = password_hash($pw, PASSWORD_BCRYPT);
		$date = date("Y-m-d");
		$stmt = $this->con->prepare("INSERT INTO users (username, email, password, date, hasaccess) VALUES (?, ?, ?, ?, ?)");
		$defaultacc = 0;
		$stmt->bind_param("ssssi", $un, $em, $hashed, $date, $defaultacc);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
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
<?php
class Account {
	private $errorArray = array();
	private $con;
	
	public function __construct($con) {
		$this->con = $con;
	}
	
	public function login($un, $pw) {
		$stmt = $this->con->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
		$stmt->bind_param("s", $un);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result->num_rows > 0) {
			$pwFromDB = $result->fetch_assoc();
			if (password_verify($pw, $pwFromDB['password'])) {
				return true;
			} else {
				array_push($this->errorArray, Constants::$loginFailed);
				return false;
			}
		} else {
			array_push($this->errorArray, Constants::$loginFailed);
			return false;
		}
	}

	public function getError($error) {
		if (!in_array($error, $this->errorArray)) {
			$error = "";
		}
		return "<span class='errorMessage'>$error</span>";
	}
}
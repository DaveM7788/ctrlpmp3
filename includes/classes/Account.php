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

	public function getError($error) {
		if (!in_array($error, $this->errorArray)) {
			$error = "";
		}
		return "<span class='errorMessage'>$error</span>";
	}
}
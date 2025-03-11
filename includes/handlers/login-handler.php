<?php
function cleanPostInput($userInput) {
	$userInput = trim($userInput);
	$userInput = stripslashes($userInput);
	$good = htmlspecialchars($userInput);
	return $good;
}

if (isset($_POST['loginButton'])) {
	// basic throttle (this app is designed to be used by one or a few users)
	usleep(200000);

	if (hash_equals(Util::hashCsrf(), $_POST['csrf'])) {
		$username = cleanPostInput($_POST['loginUsername']);
		$password = cleanPostInput($_POST['loginPassword']);
	
		$result = $account->login($username, $password);
		if ($result) {
			$_SESSION['userLoggedIn'] = $username;
			header("Location: index.php");
		}
	} else {
		$account->setLoginFailureErrorCsrf();
	}
}
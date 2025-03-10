<?php
function cleanPostInput($userInput) {
	$userInput = trim($userInput);
	$userInput = stripslashes($userInput);
	$good = htmlspecialchars($userInput);
	return $good;
}

if (isset($_POST['loginButton'])) {
	$username = cleanPostInput($_POST['loginUsername']);
	$password = cleanPostInput($_POST['loginPassword']);

	// basic throttle (this app is designed to be used by one or a few users)
	usleep(200000);

	$result = $account->login($username, $password);
	if ($result) {
		$_SESSION['userLoggedIn'] = $username;
		header("Location: index.php");
	}
}
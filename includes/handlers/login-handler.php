<?php
function exorciseEvil($evil) {
	$evil = trim($evil);
	$evil = stripslashes($evil);
	$good = htmlspecialchars($evil);
	return $good; // good now
}

if (isset($_POST['loginButton'])) {
	$username = exorciseEvil($_POST['loginUsername']);
	$password = exorciseEvil($_POST['loginPassword']);

	// naive throttle (this app is designed to be used by one or a few users)
	sleep(.3);

	$result = $account->login($username, $password);
	if ($result) {
		$_SESSION['userLoggedIn'] = $username;
		header("Location: index.php");
	}
}
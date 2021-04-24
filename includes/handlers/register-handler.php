<?php

function sanitizeFormUserName($inputText) {
	$inputText = strip_tags($inputText);  // strip html elements
	$inputText = str_replace(" ", "", $inputText); // replace all spaces with empty
	return $inputText;
}

function sanitizeFormPassword($inputText) {
	$inputText = strip_tags($inputText);  // strip html elements
	return $inputText;
}

if(isset($_POST['registerButton'])) {
	$username = sanitizeFormUserName($_POST['username']);
	$email = sanitizeFormUserName($_POST['email']);
	$email2 = sanitizeFormUserName($_POST['email2']);
	$password = sanitizeFormPassword($_POST['password']);
	$password2 = sanitizeFormPassword($_POST['password2']);

	// account created in register.php top of file
	$wasSuccessful = $account->register($username, $email, $email2, $password, $password2);
	if ($wasSuccessful) {
		$_SESSION['userLoggedIn'] = $username;  // login user in automatically !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		header("Location: index.php");
	}
}
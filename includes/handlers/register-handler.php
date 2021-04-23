<?php

function sanitizeFormUserName($inputText) {
	$inputText = strip_tags($inputText);  // strip html elements
	$inputText = str_replace(" ", "", $inputText); // replace all spaces with null
	return $inputText;
}

function sanitizeFormPassword($inputText) {
	$inputText = strip_tags($inputText);  // strip html elements
	return $inputText;
}

function sanitizeFormString($inputText) {
	$inputText = strip_tags($inputText);
	$inputText = str_replace(" ", "", $inputText);
	$inputText = ucfirst(strtolower($inputText));  
	return $inputText;
}

if(isset($_POST['registerButton'])) {
	//echo "register button was pressed";
	$username = sanitizeFormUserName($_POST['username']);
	$firstName = sanitizeFormString($_POST['firstName']);
	$lastName = sanitizeFormString($_POST['lastName']);
	$email = sanitizeFormUserName($_POST['email']);
	$email2 = sanitizeFormUserName($_POST['email2']);
	$password = sanitizeFormPassword($_POST['password']);
	$password2 = sanitizeFormPassword($_POST['password2']);

	// account created in register.php top of file
	$wasSuccessful = $account->register($username, $firstName, $lastName, $email, $email2, $password, $password2);
	if ($wasSuccessful) {
		$_SESSION['userLoggedIn'] = $username;  // login user in automatically !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		header("Location: index.php");
	}
}
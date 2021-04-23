<?php
class Constants {
	// static = do not have to create instance of class first
	public static $passwordsDoNotMatch = "Your passwords do not match";
	public static $passwordNotAlphanumeric = "Your can only contain letters and numbers";
	public static $passwordCharacters = "Your password must be between 7 and 30 characters";
	public static $emailInvalid = "Email format is invalid";
	public static $emailsDoNotMatch = "Your emails do not match";
	public static $firstNameCharacters = "Your last name must be between 2 and 25 characters";
	public static $lastNameCharacters = "Your first name must be between 2 and 25 characters";
	public static $usernameCharacters = "Your username must be between 5 and 25 characters";
	
	public static $usernameTaken = "This username has already been taken";
	public static $emailTaken = "This email has already been taken";

	public static $loginFailed = "Your username or password was incorrect";
}
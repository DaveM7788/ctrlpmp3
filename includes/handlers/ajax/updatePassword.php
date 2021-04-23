<?php
include("../../config.php");

if (!isset($_POST['username'])) {
	echo "Username not passed";
	exit();
}

if (!isset($_POST['oldPassword']) || !isset($_POST['newPassword1']) || !isset($_POST['newPassword2'])) {
	echo "Not all passwords have been passed";
	exit();
}

if ($_POST['oldPassword'] == "" || $_POST['newPassword1'] == "" || $_POST['newPassword2'] == "") {
	echo "All password fields must be filled";
	exit();
}

$username = $_POST['username'];
$oldPassword = $_POST['oldPassword'];
$newPassword1 = $_POST['newPassword1'];
$newPassword2 = $_POST['newPassword2'];

$oldHash = password_hash($oldPassword, PASSWORD_BCRYPT);

$passwordCheck = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND password='$oldHash'");
if (mysqli_num_rows($passwordCheck) != 1) {
	echo "Password is incorrect";
	exit();
}

if ($newPassword1 != $newPassword2) {
	echo "The new passwords do not match" . $newPassword1 . "    " . $newPassword2;
	exit();
}

if (preg_match('/[^A-Za-z0-9]/', $newPassword1)) {
	echo "Password must only have letters and numbers";
	exit();
}

if (strlen($newPassword1) > 30 || strlen($newPassword1) < 7) {
	echo "Passwords must be between 7 and 30 characters";
	exit();
}

$newHash = password_hash($newPassword1, PASSWORD_BCRYPT);

$query = mysqli_query($con, "UPDATE users SET password='$newHash' WHERE username='$username'");
echo "Update successful";
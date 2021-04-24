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
$oldPassword = strip_tags($oldPassword);
$newPassword1 = $_POST['newPassword1'];
$newPassword2 = $_POST['newPassword2'];

if ($newPassword1 != $newPassword2) {
	echo "The new passwords do not match" . $newPassword1 . "    " . $newPassword2;
	exit();
}

if (strlen($newPassword1) > 40 || strlen($newPassword1) < 6) {
	echo "Passwords must be between 6 and 40 characters";
	exit();
}

$query = mysqli_query($con, "SELECT * FROM users WHERE username='$username' LIMIT 1");
if (mysqli_num_rows($query) == 1) {
	while ($row = mysqli_fetch_row($query)) {
		if (!password_verify($oldPassword, $row[3])) {
			echo "Password is incorrect";
			exit();
		}
	}
} else {
	echo "Password is incorrect";
	exit();
}

$newPassword1 = strip_tags($newPassword1);
$newHash = password_hash($newPassword1, PASSWORD_BCRYPT);
$query = mysqli_query($con, "UPDATE users SET password='$newHash' WHERE username='$username'");

echo "Update successful";
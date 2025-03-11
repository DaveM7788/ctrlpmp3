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

function sanitizeInput($postInput) {
	$postInput = trim($postInput);
	$postInput = stripslashes($postInput);
	$good = htmlspecialchars($postInput);
	return $good;
}

$username = sanitizeInput($_POST['username']);
$oldPassword = sanitizeInput($_POST['oldPassword']);
$newPassword1 = sanitizeInput($_POST['newPassword1']);
$newPassword2 = sanitizeInput($_POST['newPassword2']);

if ($newPassword1 != $newPassword2) {
	echo "The new passwords do not match" . $newPassword1 . "    " . $newPassword2;
	exit();
}

if (strlen($newPassword1) > 40 || strlen($newPassword1) < 6) {
	echo "Passwords must be between 6 and 40 characters";
	exit();
}

$stmt = $con->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result and $result->num_rows == 1) {
	while ($row = $result->fetch_assoc()) {
		if (!password_verify($oldPassword, $row['password'])) {
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
$stmtUpdate = $con->prepare("UPDATE users SET password=? WHERE username=?");
$stmtUpdate->bind_param("ss", $newHash, $username);
$stmtUpdate->execute();
$stmtUpdate->close();

echo "Update successful";
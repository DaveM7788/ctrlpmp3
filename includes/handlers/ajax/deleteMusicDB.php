<?php
include("../../config.php");
include("../../classes/FuzzyData.php");
include("../../classes/Util.php");

if (!isset($_POST['csrf'])) {
	echo "Request could not be validated";
	exit();
}
if (!hash_equals(Util::hashCsrf(), $_POST['csrf'])) {
	echo "Request could not be validated";
	exit();
}

$errors = "";

$stmt = $con->prepare("DELETE FROM songs");
if (!$stmt->execute()) {
	$errors .= "could not delete songs ";
}
$stmt->close();

$stmt = $con->prepare("DELETE FROM artists");
if (!$stmt->execute()) {
	$errors .= "could not delete artists ";
}
$stmt->close();

$stmt = $con->prepare("DELETE FROM albums");
if (!$stmt->execute()) {
	$errors .= "could not delete albums ";
}
$stmt->close();

$stmt = $con->prepare("DELETE FROM genres");
if (!$stmt->execute()) {
	$errors .= "could not delete genres ";
}
$stmt->close();

$stmt = $con->prepare("DELETE FROM playlists");
if (!$stmt->execute()) {
	$errors .= "could not delete playlists ";
}
$stmt->close();

$stmt = $con->prepare("DELETE FROM playlistsongs");
if (!$stmt->execute()) {
	$errors .= "could not delete playlist songs ";
}
$stmt->close();

// need to empty out the files of fuzzy match data
$fuzzyData = new FuzzyData($con);
$fuzzyData->resetFuzzyData();

if (strcmp($errors, "") != 0) {
	echo "Errors during db reset: " . $errors;
} else {
	echo "Music deleted";
}
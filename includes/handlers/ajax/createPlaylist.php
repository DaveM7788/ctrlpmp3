<?php
include("../../config.php");
include("../../classes/Util.php");

if (!isset($_POST['csrf'])) {
	echo "Request could not be validated";
	exit();
}
if (!hash_equals(Util::hashCsrf(), $_POST['csrf'])) {
	echo "Request could not be validated";
	exit();
}

if (isset($_POST['name']) && isset($_POST['username'])) {
	$name = $_POST['name'];
	$username = $_POST['username'];
	$date = date("Y-m-d h:m:s");

	$stmt = $con->prepare("INSERT INTO playlists (name, owner, dateCreated) VALUES (?, ?, ?)");
	$stmt->bind_param("sss", $name, $username, $date);
	$stmt->execute();
	$stmt->close();
}
else {
	echo "Name or username parameters not passed";
}
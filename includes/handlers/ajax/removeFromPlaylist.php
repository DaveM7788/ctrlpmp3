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

if (isset($_POST['playlistId']) && isset($_POST['songId'])) {
	$playlistId = $_POST['playlistId'];
	$songId = $_POST['songId'];

	$stmt = $con->prepare("DELETE FROM playlistsongs WHERE playlistId=? AND songId=?");
	$stmt->bind_param("ii", $playlistId, $songId);
	$stmt->execute();
	$stmt->close();
}
else {
	echo "Playlist Id or Song Id was not passed";
}
<?php
include("../../config.php");

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
<?php
include("../../config.php");

if (isset($_POST['playlistId'])) {
	$playlistId = $_POST['playlistId'];

	$stmt = $con->prepare("DELETE FROM playlists WHERE id=?");
	$stmt->bind_param("i", $playlistId);
	$stmt->execute();
	$stmt->close();

	$stmtSongs = $con->prepare("DELETE FROM playlistsongs WHERE playlistId=?");
	$stmtSongs->bind_param("i", $playlistId);
	$stmtSongs->execute();
	$stmtSongs->close();
}
else {
	echo "Playlist Id was not passed";
}
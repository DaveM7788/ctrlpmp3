<?php
include("../../config.php");

if (isset($_POST['playlistId']) && isset($_POST['songId'])) {
	$playlistId = $_POST['playlistId'];
	$songId = $_POST['songId'];
	
	$stmt = $con->prepare("SELECT IFNULL(MAX(playlistOrder) + 1, 1) AS playlistOrder FROM playlistsongs WHERE playlistId=?");
	$stmt->bind_param("i", $playlistId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	$row = $result->fetch_assoc();
	$order = $row['playlistOrder'];

	$stmtInsert = $con->prepare("INSERT INTO playlistsongs (songId, playlistId, playlistOrder) VALUES (?, ?, ?)");
	$stmtInsert->bind_param("iii", $songId, $playlistId, $order);
	$stmtInsert->execute();
	$stmtInsert->close();
}
else {
	echo "Playlist Id or Song Id was not passed";
}
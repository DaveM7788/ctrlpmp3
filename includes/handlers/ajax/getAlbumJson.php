<?php
include("../../config.php");
if (isset($_POST['albumId'])) {
	$albumId = $_POST['albumId'];
	$stmt = $con->prepare("SELECT * FROM albums WHERE id=?");
	$stmt->bind_param("i", $albumId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	$resultArray = $result->fetch_assoc();
	echo json_encode($resultArray);
}

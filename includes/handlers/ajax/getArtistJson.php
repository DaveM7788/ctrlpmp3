<?php
include("../../config.php");
if (isset($_POST['artistId'])) {
	$artistId = $_POST['artistId'];
	$stmt = $con->prepare("SELECT * FROM artists WHERE id=?");
	$stmt->bind_param("i", $artistId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	$resultArray = $result->fetch_assoc();
	echo json_encode($resultArray);
}

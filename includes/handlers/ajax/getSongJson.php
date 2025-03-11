<?php
include("../../config.php");
if (isset($_POST['songId'])) {
	$songId = $_POST['songId'];
	$stmt = $con->prepare("SELECT * FROM songs WHERE id=?");
	$stmt->bind_param("i", $songId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	$resultArray = $result->fetch_assoc();
	echo json_encode($resultArray);
}

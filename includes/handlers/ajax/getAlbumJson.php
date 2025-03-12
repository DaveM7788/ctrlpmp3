<?php
include("../../config.php");
include("../../classes/Util.php");

if (isset($_POST['albumId']) && isset($_POST['csrf'])) {
	if (hash_equals(Util::hashCsrf(), $_POST['csrf'])) {
		$albumId = $_POST['albumId'];
		$stmt = $con->prepare("SELECT * FROM albums WHERE id=?");
		$stmt->bind_param("i", $albumId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
	
		$resultArray = $result->fetch_assoc();
		echo json_encode($resultArray);
	}
}

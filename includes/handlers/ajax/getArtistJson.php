<?php
include("../../config.php");
include("../../classes/Util.php");

if (isset($_POST['artistId']) && isset($_POST['csrf'])) {
	if (hash_equals(Util::hashCsrf(), $_POST['csrf'])) {
		$artistId = $_POST['artistId'];
		$stmt = $con->prepare("SELECT * FROM artists WHERE id=?");
		$stmt->bind_param("i", $artistId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
	
		$resultArray = $result->fetch_assoc();
		echo json_encode($resultArray);
	}
}

<?php
include("../../config.php");
include("../../classes/Util.php");
if (isset($_POST['songId']) && isset($_POST['csrf'])) {
	if (hash_equals(Util::hashCsrf(), $_POST['csrf'])) {
		$songId = $_POST['songId'];
		$stmt = $con->prepare("SELECT * FROM songs WHERE id=?");
		$stmt->bind_param("i", $songId);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
	
		$resultArray = $result->fetch_assoc();
		echo json_encode($resultArray);
	} else {
		$error = array("error"=>"Request could not be validated");
		echo json_encode($error);
	}
} else {
	$error = array("error"=>"Request variables set incorrectly");
	echo json_encode($error);
}

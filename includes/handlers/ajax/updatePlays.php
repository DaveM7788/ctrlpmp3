<?php
include("../../config.php");
include("../../classes/Util.php");

if (isset($_POST['songId']) && isset($_POST['csrf'])) {
	if (hash_equals(Util::hashCsrf(), $_POST['csrf'])) {
		$songId = $_POST['songId'];
		$stmt = $con->prepare("UPDATE songs SET plays = plays + 1 WHERE id=?");
		$stmt->bind_param("i", $songId);
		$stmt->execute();
		$stmt->close();
	}
}

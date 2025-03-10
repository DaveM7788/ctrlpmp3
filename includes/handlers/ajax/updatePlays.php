<?php
include("../../config.php");
if (isset($_POST['songId'])) {
	$songId = $_POST['songId'];
	$stmt = $con->prepare("UPDATE songs SET plays = plays + 1 WHERE id=?");
	$stmt->bind_param("i", $songId);
	$stmt->execute();
	$stmt->close();
}

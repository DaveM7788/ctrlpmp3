<?php
ob_start();  // turns on output buffering
session_start();
session_regenerate_id(true);
if (empty($_SESSION['csrfToken'])) {
	$_SESSION['csrfToken'] = bin2hex(random_bytes(32));
}

$timezone = date_default_timezone_set("America/New_York");
$con = mysqli_connect("localhost", "root", "", "ctrlpmp3");
if (mysqli_connect_errno()) {
	echo "Failed to connect: " . mysqli_connect_errno();
}

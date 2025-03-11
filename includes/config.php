<?php
ob_start();  // turns on output buffering
session_start();
session_regenerate_id(true);

$timezone = date_default_timezone_set("America/New_York");
$con = mysqli_connect("localhost", "root", "", "ctrlpmp3");
if (mysqli_connect_errno()) {
	echo "Failed to connect: " . mysqli_connect_errno();
}

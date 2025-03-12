<?php
include("../../config.php");
include("../../classes/Util.php");

if (!isset($_POST['csrf'])) {
	echo "Request could not be validated";
	exit();
}

if (!hash_equals(Util::hashCsrf(), $_POST['csrf'])) {
	echo "Request could not be validated";
	exit();
}

session_start();
session_destroy();
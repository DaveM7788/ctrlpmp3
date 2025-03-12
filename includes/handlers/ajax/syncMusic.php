<?php
include("../../config.php");
include("../../classes/SyncSong.php");
include("../../classes/FuzzyData.php");
include("../../classes/Util.php");
require_once('../../../libs/getID3-master/getid3/getid3.php');

if (!isset($_POST['csrf'])) {
	echo "Request could not be validated";
	exit();
}
if (!hash_equals(Util::hashCsrf(), $_POST['csrf'])) {
	echo "Request could not be validated";
	exit();
}

$getID3 = new getID3;
$syncSongs = new SyncSong($con);
$syncSongs->recurseDirs("../../../0_Upload_Music_Here");

$fuzzyData = new FuzzyData($con);
$fuzzyData->fuzzyDataUpdate();
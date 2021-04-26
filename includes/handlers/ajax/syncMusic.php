<?php
include("../../config.php");
include("../../classes/SyncSong.php");
require_once('../../../libs/getID3-master/getid3/getid3.php');

$getID3 = new getID3;
$syncSongs = new SyncSong($con);
$syncSongs->recurseDirs("../../../0_Upload_Music_Here");

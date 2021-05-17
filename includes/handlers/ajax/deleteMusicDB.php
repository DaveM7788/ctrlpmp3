<?php
include("../../config.php");
include("../../classes/FuzzyData.php");

$errors = "";
if (!mysqli_query($con, "DELETE FROM songs")) {
    $errors .= "could not delete songs ";
}

if (!mysqli_query($con, "DELETE FROM artists")) {
    $errors .= "could not delete artists ";
}

if (!mysqli_query($con, "DELETE FROM albums")) {
    $errors .= "could not delete albums ";
}

if (!mysqli_query($con, "DELETE FROM genres")) {
    $errors .= "could not delete genres ";
}

if (!mysqli_query($con, "DELETE FROM playlists")) {
    $errors .= "could not delete playlists ";
}

if (!mysqli_query($con, "DELETE FROM playlistsongs")) {
    $errors .= "could not delete playlist songs ";
}

// need to empty out the files of fuzzy match data
$fuzzyData = new FuzzyData($con);
$fuzzyData->resetFuzzyData();

if (strcmp($errors, "") != 0) {
    echo "Errors during db reset: " . $errors;
} else {
    echo "Music deleted";
}
<?php
include("../../config.php");
include("../../classes/FuzzyData.php");

if (!mysqli_query($con, "DELETE FROM songs")) {
    echo "could not delete songs";
}

if (!mysqli_query($con, "DELETE FROM artists")) {
    echo "could not delete artists";
}

if (!mysqli_query($con, "DELETE FROM albums")) {
    echo "could not delete albums";
}

if (!mysqli_query($con, "DELETE FROM genres")) {
    echo "could not delete genres";
}

if (!mysqli_query($con, "DELETE FROM playlists")) {
    echo "could not delete playlists";
}

if (!mysqli_query($con, "DELETE FROM playlistsongs")) {
    echo "could not delete playlist songs";
}

// need to empty out the files of fuzzy match data
$fuzzyData = new FuzzyData($con);
$fuzzyData->resetFuzzyData();
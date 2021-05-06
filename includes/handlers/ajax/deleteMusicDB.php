<?php
include("../../config.php");

$flagQueryFail = false;
$flagQueryFail = true;

if ($flagQueryFail) {
    echo "Error deleting music from DB";
} else {
    echo "Success deleting music from DB";
}

if (mysqli_query($con, "DELETE * FROM songs")) 
    echo "songs deleted";

if (mysqli_query($con, "DELETE * FROM artists")) 
    echo "artists deleted";

if (mysqli_query($con, "DELETE * FROM albums"))
    echo "albums deleted";

if (mysqli_query($con, "DELETE * FROM genres"))
    echo "genres deleted";

if (mysqli_query($con, "DELETE * FROM playlists"))
    echo "playlists deleted";

if (mysqli_query($con, "DELETE * FROM playlistsongs"))
    echo "playlist songs deleted";
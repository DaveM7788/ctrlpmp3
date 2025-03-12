<?php
include("includes/includedFiles.php");

if (isset($_GET['id'])) {
	$albumId = $_GET['id'];
}
else {
	header("Location: index.php");
}

$album = new Album($con, $albumId);
$artist = $album->getArtist();
?>

<div class="entityInfo">
	<div class="leftSection">
		<img src="<?php echo htmlspecialchars($album->getArtworkPath()); ?>">
	</div>
	<div class="rightSection">
		<h2><?php echo htmlspecialchars($album->getTitle()); ?></h2>
		<p class="pclickable" onclick="openPage('artist.php?id= <?php echo htmlspecialchars($artist->getId()) ?>')">
		By <?php echo htmlspecialchars($artist->getName()); ?></p>
		<p><?php echo htmlspecialchars($album->getNumberOfSongs()); ?> Songs</p>
		<p><?php echo htmlspecialchars($album->getAlbumDuration()); ?> Play Time</p>
	</div>
</div>

<div class="tracklistContainer">
	<ul class="tracklist">
		<?php
		$songIdArray = $album->getSongIds();
		$i = 1;
		foreach ($songIdArray as $songId) {
			$albumSong = new Song($con, $songId);
			$albumArtist = $albumSong->getArtist();

			echo "<li class='tracklistRow'>
				<div class='trackCount'>
					<img class='play' src='assets/images/icons/play-purp-small.png' onclick='setTrack(\"" . 
					htmlspecialchars($albumSong->getId()) . "\", tempPlaylist, true)'>
					<span class='trackNumber'>" . htmlspecialchars($i) . "</span>
				</div>

				<div class='trackInfo'>
					<span class='trackName'>" . htmlspecialchars($albumSong->getTitle()) . "</span>
					<span class='artistName'>" . htmlspecialchars($albumArtist->getName()) . "</span>
				</div>

				<div class='trackOptions'>
					<input type='hidden' class='songId' value='" . htmlspecialchars($albumSong->getId()) . "'>
					<img class='optionsButton' src='assets/images/icons/more-purp.png' onclick='showOptionsMenu(this)'>
				</div>

				<div class='trackDuration'>
					<span class='duration'>" . htmlspecialchars($albumSong->getDuration()) . "</span>
				</div>
			</li>";

			$i++;
		} 
		?>

		<script type="text/javascript">
			var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
			tempPlaylist = JSON.parse(tempSongIds);
		</script>

		<script>
			// scroll to top of page
			window.scrollTo(0, 0);
		</script>
	</ul>	
</div>

<nav class="optionsMenu">
	<input type="hidden" class="songId">
	<?php echo Playlist::getPlaylistDropdown($con, $userLoggedIn->getUsername()); ?>
</nav>

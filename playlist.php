<?php
include("includes/includedFiles.php");

if (isset($_GET['id'])) {
	$playlistId = $_GET['id'];
}
else {
	header("Location: index.php");
}

$playlist = new Playlist($con, $playlistId);
$owner = new User($con, $playlist->getOwner());
?>

<div class="entityInfo">
	<div class="leftSection">
		<div class="playlistImage">
			<img src="assets/images/icons/playlist-cust.png">
		</div>
	</div>
	<div class="rightSection">
		<h2><?php echo htmlspecialchars($playlist->getName()); ?></h2>
		<p>By <?php echo htmlspecialchars($playlist->getOwner()); ?></p>
		<p><?php echo htmlspecialchars($playlist->getNumberOfSongs()); ?> Songs</p>
		<button class="button" onclick="deletePlaylist('<?php echo htmlspecialchars($playlistId); ?>')">Delete Playlist</button>
	</div>
</div>

<div class="tracklistContainer">
	<ul class="tracklist">
		<?php
		$songIdArray = $playlist->getSongIds();
		$i = 1;
		foreach ($songIdArray as $songId) {
			$playlistSong = new Song($con, $songId);
			$songArtist = $playlistSong->getArtist();

			echo "<li class='tracklistRow'>
				<div class='trackCount'>
					<img class='play' src='assets/images/icons/play-purp-small.png' onclick='setTrack(\"" . htmlspecialchars($playlistSong->getId()) . "\", tempPlaylist, true)'>
					<span class='trackNumber'>$i</span>
				</div>

				<div class='trackInfo'>
					<span class='trackName'>" . htmlspecialchars($playlistSong->getTitle()) . "</span>
					<span class='artistName'>" . htmlspecialchars($songArtist->getName()) . "</span>
				</div>

				<div class='trackOptions'>
					<input type='hidden' class='songId' value='" . htmlspecialchars($playlistSong->getId()) . "'>
					<img class='optionsButton' src='assets/images/icons/more-purp.png' onclick='showOptionsMenu(this)'>
				</div>

				<div class='trackDuration'>
					<span class='duration'>" . htmlspecialchars($playlistSong->getDuration()) . "</span>
				</div>
			</li>";

			$i++;
		} 
		?>

		<script type="text/javascript">
			var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
			tempPlaylist = JSON.parse(tempSongIds);
		</script>
	</ul>	
</div>

<nav class="optionsMenu">
	<input type="hidden" class="songId">
	<?php echo Playlist::getPlaylistDropdown($con, $userLoggedIn->getUsername()); ?>
	<div class="item" onclick="removeFromPlaylist(this, '<?php echo htmlspecialchars($playlistId); ?>')">Remove from Playlist</div>
	<option></option>
</nav>
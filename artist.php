<?php
include("includes/includedFiles.php");

if (isset($_GET['id'])) {
	$artistId = $_GET['id'];
}
else {
	header("Location: index.php");
}

$artist = new Artist($con, $artistId);
?>

<div class="entityInfo borderBottom">
	<div class="centerSection">
		<div class="artistInfo">
			<h1 class="artistName"><?php echo htmlspecialchars($artist->getName()); ?></h1>

			<div class="headerButtons">
				<button class="button green" onclick="playFirstSong()">Play</button>
			</div>
		</div>
	</div>
</div>

<div class="tracklistContainer borderBottom">
	<h2>Songs</h2>
	<ul class="tracklist">
		<?php
		$songIdArray = $artist->getSongIds();
		$i = 1;
		foreach ($songIdArray as $songId) {
			$albumSong = new Song($con, $songId);
			$albumArtist = $albumSong->getArtist();

			echo "<li class='tracklistRow'>
				<div class='trackCount'>
					<img class='play' src='assets/images/icons/play-purp-small.png' onclick='setTrack(\"" . htmlspecialchars($albumSong->getId()) . "\", tempPlaylist, true)'>
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
	</ul>	
</div>

<div class="gridViewContainer">
	<h2>Albums</h2>
	<?php
	//$albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE artist='$artistId'");
	$stmt = $con->prepare("SELECT * FROM albums WHERE artist=?");
	$stmt->bind_param("i", $artistId);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	while ($row = $result->fetch_assoc()) {
		echo "<div class='gridViewItem'>
				<span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . htmlspecialchars($row['id']) . "\")'>
					<img src='" . htmlspecialchars($row['artworkPath']) . "'>
					<div class='gridViewInfo'>"
					. htmlspecialchars($row['title']) .
					"</div>
				</span>
			  </div>
			 ";
	}
	?>
</div>

<nav class="optionsMenu">
	<input type="hidden" class="songId">
	<?php echo Playlist::getPlaylistDropdown($con, $userLoggedIn->getUsername()); ?>
</nav>
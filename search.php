<?php
include("includes/includedFiles.php");

if (isset($_GET['term'])) {
	$term = urldecode($_GET['term']);
}
else {
	$term = "";
}
?>

<div class="searchContainer">
<h4>Search for an artist, album, or song</h4>
<input type="text" class="searchInput" placeholder="Start typing" value="<?php echo htmlspecialchars($term); ?>" onfocus="var temp_val=this.value; this.value=''; this.value=temp_val" />
</div>

<script>
	$(".searchInput").focus();

	$(function() {
		// uses timer from script.js
		$(".searchInput").keyup(function() {
			clearTimeout(timer); // cancel existing timer as soon as user types
			timer = setTimeout(function() {
				var val = $(".searchInput").val();
				openPage("search.php?term=" + val);
			}, 600)  // every time user types, timer starts, searches db after .6 sec
		})
	})
</script>

<?php 
if ($term == "") {
	exit();
}
?>

<div class="tracklistContainer borderBottom">
	<h2>Songs</h2>
	<ul class="tracklist">
		<?php
		$term = "%$term%";
		$sql = "SELECT id FROM songs WHERE title LIKE ? LIMIT 15";
		$stmt = $con->prepare($sql); 
		$stmt->bind_param("s", $term);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		if ($result and $result->num_rows == 0) {
			echo "<span class='noResults'>No songs found</span>";
		}


		$songIdArray = array();
		$i = 1;
		while ($row = $result->fetch_assoc()) {
			
			if ($i > 15) {
				break;
			}

			array_push($songIdArray, $row['id']);

			$albumSong = new Song($con, $row['id']);
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

<div class="artistsContainer borderBottom">
	<h2>Artists</h2>
	<?php
	$term = "%$term%";
	$sql = "SELECT id FROM artists WHERE name LIKE ? LIMIT 15";
	$stmt = $con->prepare($sql); 
	$stmt->bind_param("s", $term);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();

	if ($result and $result->num_rows == 0) {
		echo "<span class='noResults'>No artists found</span>";
	}

	while ($row = $result->fetch_assoc()) {
		$artistFound = new Artist($con, $row['id']);
		echo "<div class='searchResultRow'>
		<div class='artistName'>
			<span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . htmlspecialchars($artistFound->getId()) . "\")'>
			" . htmlspecialchars($artistFound->getName()) . "
			</span>
		</div>
		</div>";
	}
	?>
</div>

<div class="gridViewContainer">
	<h2>Albums</h2>
	<?php
	$term = "%$term%";
	$sql = "SELECT * FROM albums WHERE title LIKE ? LIMIT 10";
	$stmt = $con->prepare($sql); 
	$stmt->bind_param("s", $term);
	$stmt->execute();
	$result = $stmt->get_result();
	$stmt->close();
	
	if ($result and $result->num_rows == 0) {
		echo "<span class='noResults'>No albums found</span>";
	}

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
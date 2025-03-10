<?php
include("includes/includedFiles.php");
?>

<div class="playlistsContainer">
	<div class="gridViewContainer">
		<h1 class="pageHeadingBig">Playlists</h1>
		<div class="buttonItems">
			<button class="button green" onclick="createPlaylist()">New Playlist</button>
		</div>

		<?php
		$username = $userLoggedIn->getUsername();

		$stmt = $con->prepare("SELECT * FROM playlists WHERE owner=?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result and $result->num_rows == 0) {
			echo "<span class='noResults'>No playlists found</span>";
		}

		while ($row = $result->fetch_assoc()) {
			$playlist = new Playlist($con, $row);
			echo "<div class='gridViewItem' role='link' tabindex='0' onclick='openPage(\"playlist.php?id=" . htmlspecialchars($playlist->getId()) . "\")'>
						<div class='playlistImage'>
						<img src='assets/images/icons/playlist-cust.png'>
						</div>

						<div class='gridViewInfo'>"
						. htmlspecialchars($playlist->getName()) .
						"</div>
				  </div>
				 ";
		}		
		?>
	</div>
	
</div>
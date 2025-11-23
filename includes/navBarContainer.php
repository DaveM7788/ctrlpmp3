<div id="navBarContainer">
	<nav class="navBar">
		<span role="link" tabindex="0" onclick="openPage('index.php')" class="logo">
			<table>
			<tr>
				<td><img src="assets/images/controlp_sq_jpg.jpg" alt="Logo"></td>
				<td><p id="logoText">Ctrl-P MP3</p></td>
			</tr>
			</table>
		</span>

		<div class="group">
			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('search.php')" class="navItemLink">DB Search
				</span>
			</div>
		</div>

		<div class="group">
			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('yourMusic.php')" class="navItemLink">Your Music</span>
			</div>
			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('allPlaylists.php')" class="navItemLink">Playlists</span>
			</div>
			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('sync.php')" class="navItemLink">Sync Music</span>
			</div>
			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('settings.php')" 
				class="navItemLink">Settings for <?php echo htmlspecialchars($userLoggedIn->getUsername()); ?></span>
			</div>
			<div class="navItem">
				<span role="link" tabindex="0" onclick="logout()" class="navItemLink">Log Out</span>
			</div>
		</div>
	</nav>
</div>
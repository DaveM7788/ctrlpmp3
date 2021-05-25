<?php
include("includes/includedFiles.php");
?>

<div class="entityInfo">
	<div class="centerSection">
		<div class="userInfo">
			<h1>Sync Music</h1>
		</div>
	</div>

	<div class="centerPara">
		<p>Add music into the folder : 0_Upload_Music_Here</p>
	</div>

	<div class="centerPara">
		<p>Click the sync button to collect meta data and insert songs into the database</p>
		<p>Note: The first sync may take a few minutes</p>
	</div>

	<div class="buttonItems">
		<button class="button" id="syncMusicBtn" onclick="syncMusic()">Sync Music</button>
	</div>

	<br>

	<div class="centerParaMore">
		<p>Restore music database to empty state</p>
	</div>

	<div class="buttonItems">
		<button class="button" onclick="deleteMusicDB()">Delete Music</button>
	</div>

	<div class="centerParaMore">
		<p>It's recommended to hard refresh after syncing or deleting music to ensure fuzzy match works properly</p>
		<p>Chrome and Firefox: Press Ctrl-Shift-R on Windows/Linux or Cmd-Shift-R on Mac</p>
	</div>
</div>

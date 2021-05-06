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
	</div>

	<div class="buttonItems">
		<button class="button" onclick="syncMusic()">Sync Music</button>
	</div>

	<div class="centerParaMore">
		<p>Restore music database to empty state</p>
	</div>

	<div class="buttonItems">
		<button class="button" onclick="deleteMusicDB()">Delete Music</button>
	</div>
</div>
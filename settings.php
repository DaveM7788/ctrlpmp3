<?php
include("includes/includedFiles.php");
?>

<div class="entityInfo">
	<div class="centerSection">
		<div class="userInfo">
			<h1><?php echo htmlspecialchars($userLoggedIn->getUsername()); ?></h1>
		</div>
	</div>

	<div class="buttonItems">
		<button class="button" onclick="openPage('updateDetails.php')">Change Password</button>
		<button class="button" onclick="logout()">Log Out</button>
	</div>
</div>
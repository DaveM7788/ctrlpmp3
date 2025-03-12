<?php
include("includes/config.php");
include("includes/classes/User.php");
include("includes/classes/Artist.php");
include("includes/classes/Album.php");
include("includes/classes/Song.php");
include("includes/classes/Playlist.php");
include("includes/classes/Util.php");

if (isset($_SESSION['userLoggedIn'])) {
	$userLoggedIn = new User($con, $_SESSION['userLoggedIn']);
	$usernameSanitized = htmlspecialchars($userLoggedIn->getUsername());
	echo "<script>userLoggedIn='$usernameSanitized'</script>";
} else {
	header("Location: register.php");
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Ctrl-P MP3</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="assets/js/script.js"></script>
	<script src="assets/js/fuzzy_match_data.js"></script>
	<script src="assets/js/fuzzy_match_dataids.js"></script>
	<script src="assets/js/fuzzy.js"></script>
	<script src="assets/js/ctrlpui.js"></script>
	<link rel="icon" href="assets/images/controlp_sq_jpg.jpg">
</head>
<body>

	<input type="hidden" id="csrfHeader" value="<?php echo Util::hashCsrf(); ?>">

	<div id="ctrlpModal" class="ctrlp-modal-normal">
		<div class="ctrlp-modal-normal-content">
			<input type="text" id="ctrlpInput" placeholder="Type to Fuzzy Search" class="ctrlp-modal-input" autocomplete="off">
			<ul id="ctrlpResultsList">
		</div>
	</div>

	<div id="mainContainer">
		<div id="topContainer">
			<?php include("includes/navBarContainer.php"); ?>
			<div id="mainViewContainer">
				<div id="mainContent">
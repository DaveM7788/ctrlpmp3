<?php 
include("includes/includedFiles.php");
?>

<h1 class="pageHeadingBig">Your Music</h1>
<div class="gridViewContainer">
	<?php
	$stmt = $con->prepare("SELECT * FROM albums ORDER BY title");
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
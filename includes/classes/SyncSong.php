<?php
class SyncSong {
	private $con;

	public function __construct($con) {
		$this->con = $con;
	}

	public function recurseDirs($path) {
		$getID3 = new getID3;
		$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
		$files = array();
		foreach ($rii as $file) {
			if ($file->isDir()) {
				continue;
			}
			$files[] = $file->getPathname();
		}

		foreach ($files as $song) {
			if ($this->isAudioFile($song)) {
				$ThisFileInfo = $getID3->analyze($song);
				$getID3->CopyTagsToComments($ThisFileInfo);
				$this->getAudioData($ThisFileInfo);
			}
		}

		$songCount = $this->getTotalSongCount();
		if ($songCount != -1) {
			echo "Sync Music Complete: " . $songCount . " songs in the DB";
		} else {
			echo "Possible error during sync operation";
		}
	}

	public function isAudioFile($song) {
		$ending = substr($song, -4);
		if ($ending === ".mp3" || $ending === ".ogg" || $ending === ".wav" || $ending === ".aac") {
			return true;
		}
		$ending = substr($song, -5);
		if ($ending === ".flac" || $ending === ".aiff" ) {
			return true;
		}
		return false;
	}

	public function getAudioData($f) {
		$songDuration = "0";
		$songTitle = "None";
		$songAlbum = "None";
		$songArtist = "None";
		$songGenre = "None";
		$songTrackNum = "None";
		$songImg = "None";
		$songMime = "None";
		$songPath = $f['filenamepath'];
		$position = strpos($songPath, "0_Upload_Music_Here");
		$songPath = substr($songPath, $position);

		if (isset($f['playtime_string'])) {
			// playtime in minutes:seconds, formatted string -> goes to database
			$songDuration = $f['playtime_string'];
		}

		if (isset($f['comments']['title'][0])) {
			$songTitle = $f['comments']['title'][0];
		}
		else {
			$songTitle = $f['filename'];
		}

		if (isset($f['comments']['album'][0])) {
			$songAlbum = $f['comments']['album'][0];
		}

		if (isset($f['comments']['artist'][0])) {
			$songArtist = $f['comments']['artist'][0];
		}

		if (isset($f['comments']['genre'][0])) {
			$songGenre = $f['comments']['genre'][0];
		}

		if (isset($f['comments']['track_number'][0])) {
			$songTrackNum = $f['comments']['track_number'][0];
		}

		if (isset($f['comments']['picture'][0]['data'])) {
			$songImg = $f['comments']['picture'][0]['data'];
			$songMime = $f['comments']['picture'][0]['image_mime'];
		}

		$mArtistId = $this->artistInsert($songArtist);
		$mGenreId = $this->genreInsert($songGenre);
		$mArtworkPath = $this->alubmArtSave($songImg, $songMime, $songAlbum);
		$mAlbumId = $this->albumInsert($songAlbum, $mArtistId, $mGenreId, $mArtworkPath);
		$this->songInsert($songTitle, $mArtistId, $mAlbumId, $mGenreId, $songDuration,
									$songPath, $songTrackNum, 0);
	}

	public function artistInsert($artist) {
		$artistID = NULL;
		$stmt = $this->con->prepare("SELECT id FROM artists WHERE name=?");
		$stmt->bind_param("s", $artist);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		if ($result and $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			// artist already exists in db, use the ID that has already been set
			$artistID = $row['id'];
		} else {
			$ins = $this->con->prepare("INSERT INTO artists (name) VALUES(?)");
			$ins->bind_param("s", $artist);
			$ins->execute();
			$ins->get_result();
			$ins->close();
			$artistID = mysqli_insert_id($this->con);
		}
		return $artistID;
	}

	public function genreInsert($genre) {		
		$genreID = NULL;
		$genreExistsStmt = $this->con->prepare("SELECT * FROM genres WHERE name=?");
		$genreExistsStmt->bind_param("s", $genre);
		$genreExistsStmt->execute();
		$result = $genreExistsStmt->get_result();
		$genreExistsStmt->close();
		if ($result and $result->num_rows > 0) {
			// genre already exists in db, use the ID that has already been set
			$row = $result->fetch_assoc();
			$genreID = $row['id'];
		}
		else {
			$ins = $this->con->prepare("INSERT INTO genres (name) VALUES(?)");
			$ins->bind_param("s", $genre);
			$ins->execute();
			$ins->get_result();
			$ins->close();
			$genreID = mysqli_insert_id($this->con);
		}

		return $genreID;
	}

	public function alubmArtSave($img, $mime, $songAlbum) {
		if ($img != "None") {
			$extension = ".jpg";
			if ($mime === "image/jpeg" || $mime === "image/jpg" || $mime === "jpeg" || $mime === "jpg") {
				$extension = ".jpg";
			}
			elseif ($mime === "image/png" || $mime === "png") {
				$extension = ".png";
			}
			elseif ($mime === "image/bmp" || $mime === ".bmp") {
				$extension = ".bmp";
			}
			else {
				echo "album art image error: extension not found";
			}

			// Remove anything which isn't a word, whitespace, number
			// or any of the following caracters -_~,;[]().
			$songAlbum = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $songAlbum);
			// Remove any runs of periods
			$songAlbum = mb_ereg_replace("([\.]{2,})", '', $songAlbum);

			$imgDirectoryPath = "../../../assets/images/artwork";
			$albumArtsAlready = scandir($imgDirectoryPath);
			$imgFilename = $songAlbum . $extension;
			if (!in_array($imgFilename, $albumArtsAlready)) {
				$imgFile = fopen($imgDirectoryPath . '/' . $imgFilename, "wb");
				fwrite($imgFile, $img);
				fclose($imgFile);
			}
			return 'assets/images/artwork/' . $imgFilename;
		}
		return 'assets/images/controlp_sq_jpg.jpg';
	}

	public function albumInsert($title, $artist, $genre, $artworkPath) {
		$albumID = NULL;
		$albumExistsStmt = $this->con->prepare("SELECT * FROM albums WHERE title=?");
		$albumExistsStmt->bind_param("s", $title);
		$albumExistsStmt->execute();
		$result = $albumExistsStmt->get_result();
		$albumExistsStmt->close();
		if ($result and $result->num_rows > 0) {
			// artist already exists in db, use the album ID that has already been set
			$row = $result->fetch_assoc();
			$albumID = $row['id'];
		}
		else {
			$ins = $this->con->prepare("INSERT INTO albums (title, artist, genre, artworkPath) VALUE (?, ?, ?, ?)");
			$ins->bind_param("siis", $title, $artist, $genre, $artworkPath);
			$ins->execute();
			$ins->close();
			$albumID = mysqli_insert_id($this->con);
		}

		return $albumID;
	}

	public function songInsert($title, $artist, $album, $genre, $duration, $path, $albumOrder, $plays) {
		$songID = NULL;
		$stmt = $this->con->prepare("SELECT * FROM songs WHERE title=?");
		$stmt->bind_param("s", $title);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		if ($result and $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$songID = $row['id'];
		}
		else {
			$stmt = $this->con->prepare("INSERT INTO songs (title, artist, album, genre, duration, path, albumOrder,
				plays) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("siiissii", $title, $artist, $album, $genre, $duration, $path, $albumOrder, $plays);
			$stmt->execute();
			$stmt->close();
			$songID = mysqli_insert_id($this->con);
		}
		return $songID;
	}

	public function getTotalSongCount() {
		$stmt = $this->con->prepare("SELECT COUNT(1) FROM songs");
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$row = $result->fetch_row();
		if (!empty($row)) {
			return $row[0];
		}
		return -1;
	}
}
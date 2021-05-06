<?php
class SyncSong {
	private $con;

	public function __construct($con) {
		$this->con = $con;
	}

	// need to ignore readme.md     and what to do with non supported characters (japanese / chinese / etc.)
	// need to create default.jpg
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
			$ending = substr($song, -3);
			if ($ending != ".md" or $ending != "txt") {
				$ThisFileInfo = $getID3->analyze($song);
				$getID3->CopyTagsToComments($ThisFileInfo);
				$this->getAudioData($ThisFileInfo);
			}
		}
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
		//$songTitle = preg_replace("/^[a-zA-Z0-9]+$/", "", $songTitle);
		$this->songInsert($songTitle, $mArtistId, $mAlbumId, $mGenreId, $songDuration,
									$songPath, $songTrackNum, 0);
	}

	public function getNextId($whichTable) {
		$returnId = 0;
		$query = mysqli_query($this->con, "SELECT max(id) FROM '$whichTable'");
		while ($row = mysqli_fetch_row($query)) {
			$returnId = $row[0];
		}
		return $returnId;
	}

	public function artistInsert($artist) {
		$artistID = 0;
		$artistExists = mysqli_query($this->con, "SELECT * FROM artists WHERE name='$artist'");
		$row = mysqli_fetch_array($artistExists);
		if (!empty($row)) {
			// artist already exists in db, use the artist ID that has already been set
			$artistID = $row['id'];
		}
		else {
			mysqli_query($this->con, "INSERT INTO artists VALUES(NULL, '$artist')");
			$artistID = $this->getNextId('artists');
		}

		return $artistID;
	}

	public function genreInsert($genre) {
		$genreID = 0;
		$genreExists = mysqli_query($this->con, "SELECT * FROM genres WHERE name='$genre'");
		$row = mysqli_fetch_array($genreExists);
		if (!empty($row)) {
			// artist already exists in db, use the artist ID that has already been set
			$genreID = $row['id'];
		}
		else {
			mysqli_query($this->con, "INSERT INTO genres VALUES(NULL, '$genre')");
			$genreID = $this->getNextId('genres');
		}

		return $genreID;
	}

	public function alubmArtSave($img, $mime, $songAlbum) {
		//echo $mime;
		if ($img != "None") {
			$extension = ".jpg";
			if ($mime == "image/jpeg" || $mime == "image/jpg" || $mime == "jpeg" || $mime == "jpg") {
				$extension = ".jpg";
			}
			elseif ($mime == "image/png" || $mime == "png") {
				$extension = ".png";
			}
			elseif ($mime == "image/bmp" || $mime == ".bmp") {
				$extension = ".bmp";
			}
			else {
				echo "extension not found";
			}

			// Remove anything which isn't a word, whitespace, number
			// or any of the following caracters -_~,;[]().
			$songAlbum = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $songAlbum);
			// Remove any runs of periods
			$songAlbum = mb_ereg_replace("([\.]{2,})", '', $songAlbum);

			$imgDirectoryPath = "../../../assets/images/artwork";
			//$albumArtsAlready = scandir('images');
			$albumArtsAlready = scandir($imgDirectoryPath);
			$imgFilename = $songAlbum . $extension;
			if (!in_array($imgFilename, $albumArtsAlready)) {
				//$imgFile = fopen('images/' . $imgFilename, "wb");
				$imgFile = fopen($imgDirectoryPath . '/' . $imgFilename, "wb");
				fwrite($imgFile, $img);
				fclose($imgFile);
			}
			//return 'images/' . $imgFilename;
			return 'assets/images/artwork/' . $imgFilename;
		}
		//return 'images/default.jpg';
		return 'assets/images/artwork/default.jpg';
	}

	public function albumInsert($title, $artist, $genre, $artworkPath) {
		$albumID = 0;
		$albumExists = mysqli_query($this->con, "SELECT * FROM albums WHERE title='$title'");
		$row = mysqli_fetch_array($albumExists);
		if (!empty($row)) {
			// artist already exists in db, use the album ID that has already been set
			$albumID = $row['id'];
		}
		else {
			mysqli_query($this->con, "INSERT INTO albums VALUES(NULL, '$title', '$artist', '$genre', '$artworkPath')");
			$albumID = $this->getNextId('albums');
		}

		return $albumID;
	}

	public function songInsert($title, $artist, $album, $genre, $duration, $path, $albumOrder, $plays) {
		$songID = 0;
		$stmt = $this->con->prepare("SELECT * FROM songs WHERE title=?");
		$stmt->bind_param("s", $title);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$songID = $row['id'];
		}
		else {
			$stmt = $this->con->prepare("INSERT INTO songs (title, artist, album, genre, duration, path, albumOrder,
				plays) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("siiissii", $title, $artist, $album, $genre, $duration, $path, $albumOrder, $plays);
			$stmt->execute();
			$stmt->close();
			$songID = $this->getNextId('songs');
		}
		return $songID;
	}
}
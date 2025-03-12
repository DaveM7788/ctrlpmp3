<?php

class Album {
	private $con;
	private $id;
	private $title;
	private $artistId;
	private $genre;
	private $artworkPath;

	public function __construct($con, $id) {
		$this->con = $con;
		$this->id = $id;

		$stmt = $this->con->prepare("SELECT * FROM albums WHERE id=?");
		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		$album = $result->fetch_assoc();
		$this->title = $album['title'];
		$this->artistId = $album['artist'];
		$this->genre = $album['genre'];
		$this->artworkPath = $album['artworkPath'];
	}

	public function getTitle() {
		return $this->title;
	}

	public function getArtworkPath() {
		return $this->artworkPath;
	}

	public function getGenre() {
		return $this->genre;
	}

	public function getArtist() {
		return new Artist($this->con, $this->artistId);
	}

	public function getNumberOfSongs() {
		$stmt = $this->con->prepare("SELECT id FROM songs WHERE album=?");
		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		return $result->num_rows;
	}

	public function getAlbumDuration() {
		$stmt = $this->con->prepare("SELECT duration FROM songs WHERE album=?");
		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		$totalSecondsAll = 0;
		while ($row = $result->fetch_assoc()) {
			$oneSongDuration = $row['duration'];
			$timeArray = explode(":", $oneSongDuration);
			$totalSecondsSong = 0;
			if (sizeof($timeArray) == 1) {
				$totalSecondsSong = (int)$timeArray[0];
			} else if (sizeof($timeArray) == 2) {
				$seconds = (int)$timeArray[1];
				$minutes = (int)$timeArray[0];
				$totalSecondsSong = $seconds + ($minutes * 60);
			} else if (sizeof($timeArray) == 3) {
				$seconds = (int)$timeArray[2];
				$minutes = (int)$timeArray[1];
				$hours = (int)$timeArray[0];
				$totalSecondsSong = $seconds + ($minutes * 60) + ($hours * 3600);
			}
			$totalSecondsAll = $totalSecondsAll + $totalSecondsSong;
		}
		return $this->formatSecondsHhMmSs($totalSecondsAll, ":");
	}

	public function getSongIds() {
		$stmt = $this->con->prepare("SELECT id FROM songs WHERE album=? ORDER BY albumOrder ASC");
		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		$arrayIds = array();
		while ($row = $result->fetch_assoc()) {
			array_push($arrayIds, $row['id']);
		}
		return $arrayIds;
	}

	public function formatSecondsHhMmSs($t,$f=':') {
        return sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
    }
}
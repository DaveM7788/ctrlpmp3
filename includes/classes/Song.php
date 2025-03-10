<?php
class Song {
	private $con;
	private $id;
	private $mysqliData;
	private $title;
	private $genre;
	private $artistId;
	private $albumId;
	private $duration;
	private $path;

	public function __construct($con, $id) {
		$this->con = $con;
		$this->id = $id;

		$stmt = $this->con->prepare("SELECT * FROM songs WHERE id=?");
		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		$this->mysqliData = $result->fetch_assoc();

		$this->title = $this->mysqliData['title'];
		$this->artistId = $this->mysqliData['artist'];
		$this->albumId = $this->mysqliData['album'];
		$this->genre = $this->mysqliData['genre'];
		$this->duration = $this->mysqliData['duration'];
		$this->path = $this->mysqliData['path'];
	}

	public function getId() {
		return $this->id;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getArtist() {
		return new Artist($this->con, $this->artistId);
	}

	public function getAlbum() {
		return new Album($this->con, $this->albumId);
	}

	public function getPath() {
		return $this->path;
	}

	public function getDuration() {
		return $this->duration;
	}

	public function getGenre() {
		return $this->genre;
	}

	public function getMysqliData() {
		return $this->mysqliData;
	}
}
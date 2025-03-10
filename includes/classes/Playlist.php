<?php
class Playlist {
	private $con;
	private $id;
	private $name;
	private $owner;

	public function __construct($con, $data) {
		if (!is_array($data)) {
			// data is an id (string), needs to be an array
			$stmt = $con->prepare("SELECT * FROM playlists WHERE id=?");
			$stmt->bind_param("i", $data);
			$stmt->execute();
			$result = $stmt->get_result();
			$stmt->close();
			$data = $result->fetch_assoc();
		}

		$this->con = $con;
		$this->id = $data['id'];
		$this->name = $data['name'];
		$this->owner = $data['owner'];
	}

	public function getName() {
		return $this->name;
	}

	public function getOwner() {
		return $this->owner;
	}

	public function getId() {
		return $this->id;
	}

	public function getNumberOfSongs() {
		$stmt = $this->con->prepare("SELECT songId FROM playlistsongs WHERE playlistId=?");
		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		return $result->num_rows;
	}

	public function getSongIds() {
		$stmt = $this->con->prepare("SELECT songId FROM playlistsongs WHERE playlistId=? ORDER BY playlistOrder ASC");
		$stmt->bind_param("i", $this->id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		$arrayIds = array();
		while ($row = $result->fetch_assoc()) {
			array_push($arrayIds, $row['songId']);
		}
		return $arrayIds;
	}

	// can call without creating instance of Playlist class
	public static function getPlaylistDropdown($con, $username) {
		$dropdown = '<select class="item playlist">
					<option value="">Add to playlist</option>';

		$stmt = $con->prepare("SELECT id, name FROM playlists WHERE owner=?");
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();
		while ($row = $result->fetch_assoc()) {
			$idSanitized = htmlspecialchars($row['id']);
			$nameSanitized = htmlspecialchars($row['name']);
			$dropdown = $dropdown . "<option value='$idSanitized'>$nameSanitized</option>";
		}
		return $dropdown . "</select>";
	}
}
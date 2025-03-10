<?php
class Artist {
	private $con;
	private $id;

	public function __construct($con, $id) {
		$this->con = $con;
		$this->id = $id;
	}

	public function getName() {
		$stmt = $this->con->prepare("SELECT name FROM artists WHERE id=?");
		$stmt->bind_param("s", $this->id);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmt->close();

		if ($result and $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row['name'];
		}
	}

	public function getSongIds() {
		$stmt = $this->con->prepare("SELECT id FROM songs WHERE artist=? ORDER BY plays ASC");
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

	public function getId() {
		return $this->id;
	}
}
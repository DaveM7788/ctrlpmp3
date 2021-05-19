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
		$query = mysqli_query($this->con, "SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays ASC");
		$arrayIds = array();
		while ($row = mysqli_fetch_array($query)) {
			array_push($arrayIds, $row['id']);
		}
		return $arrayIds;
	}

	public function getId() {
		return $this->id;
	}
}
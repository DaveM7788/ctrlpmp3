<?php
class FuzzyData {

    private $songIDArray = array();
	private $songTitleArray = array();

    public function __construct($con) {
		$this->con = $con;
	}

    public function writeFuzzyDataSource() {
		if (count($this->songIDArray) > 0 and count($this->songTitleArray) > 0) {
			$beginJSON = 'var dataSets = {"songs" : [';
			$beginJSONIds = 'var dataSets = {"songids" : [';
			$endJSON = ']}';
			$middleJSON = '';
			$middleJSONIds = '';

			foreach ($this->songTitleArray as $key=>$title) {
				$builder = '"' . $title . '",';
				$builderId = '"' . $this->songIDArray[$key] . '",';
				$middleJSON .= $builder;
				$middleJSONIds .= $builderId;
			}

			$complete = $beginJSON . substr($middleJSON, 0, -1) . $endJSON;
			$completeIds = $beginJSONIds . substr($middleJSONIds, 0, -1) . $endJSON;
			echo "complete" . $complete;
			$file = fopen("../../../assets/js/fuzzy_match_data.js", "w");
			fwrite($file, $complete);
			fclose($file);

			$fileIds = fopen("../../../assets/js/fuzzy_match_dataids.js", "w");
			fwrite($fileIds, $completeIds);
			fclose($fileIds);
		}
	}

    public function fuzzyDataUpdate() {
        $query = mysqli_query($this->con, "SELECT id,title FROM songs");
        while ($row = mysqli_fetch_array($query)) {
			array_push($this->songIDArray, $row['id']);
            array_push($this->songTitleArray, $row['title']);
		}

        $this->writeFuzzyDataSource();
    }
}
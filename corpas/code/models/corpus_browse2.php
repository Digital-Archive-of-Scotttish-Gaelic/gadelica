<?php


namespace models;


class corpus_browse2
{

	private $_db;   //an instance of models\database
	private $_parent; //an (optional) instance of models\text
	private $_id, $_title, $_date, $_filepath, $_transformedText;
	private $_writers = array();  //array of models\writer objects

	public function __construct($id) {
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_id = $id;
		if ($id != "0") { // not the root corpus node, i.e. a text
			$this->_load();
		}
	}

	/**
	 * Populates the object from the DB
	 */
	private function _load() {
		$sql = <<<SQL
			SELECT title, partOf, filepath, date
				FROM text
				WHERE id = :id
SQL;
		$results = $this->_db->fetch($sql, array(":id" => $this->getId()));
		$textData = $results[0];
		$this->_setTitle($textData["title"]);
		if ($parentId = $textData["partOf"]) {    //create a parent text if required
			$this->_setParent($parentId);
		}
		else { // MMMMMMM
			$this->_setParent("0");
		}
		if ($filepath = $textData["filepath"]) {
			$this->_setFilepath($filepath);
		}
		if ($date = $textData["date"]) {
			$this->_setDate($date);
		}
		$this->_setWriters();
	}

	// Setters

	/**
	 * Creates a new text instance for parent text
	 * @param $id
	 */
	private function _setParent($id) {
		$this->_parent = new corpus_browse2($id);
	}

	private function _setTitle($title) {
		$this->_title = $title;
	}

	private function _setFilepath($filepath) {
		$this->_filepath = $filepath;
	}

	private function _setDate($date) {
		$this->_date = $date;
	}

	/**
	 * Populates the array of models\writer objects for this text
	 */
	private function _setWriters() {
		$sql = <<<SQL
			SELECT writer_id
				FROM text_writer
				WHERE text_id = :id
SQL;
		$results = $this->_db->fetch($sql, array(":id" => $this->getId()));
		foreach ($results as $result) {
			$this->_writers[] = new writer_sql($result["writer_id"]);
		}
	}

	private function _applyXSLT() {
		if ($this->getFilepath() != '') {
			$text = new \SimpleXMLElement("../xml/" . $this->getFilepath(), 0, true);
			$xsl = new \DOMDocument;
			$xsl->load('corpus.xsl');
			$proc = new \XSLTProcessor;
			$proc->importStyleSheet($xsl);
			return $proc->transformToXML($text);
		}
	}

	//Getters

	public function getTextId() {
		return $this->_textId;
	}

	public function getId() {
		return $this->_id;
	}

	public function getTitle() {
		return $this->_title;
	}

	public function getDate() {
		return $this->_date;
	}

	/**
	 * @return models\text object
	 */
	public function getParent() {
		return $this->_parent;
	}

	/**
	 * Get child text info (on the fly to cut down on memory overhead)
	 * @return array of associative info ("id" => "title")
	 */
	public function getChildInfo() {
		$childInfo = array();
		if ($this->_id != "0") { // MMMMMMM
		  $sql = <<<SQL
			  SELECT id, title FROM text WHERE partOf = :id ORDER BY id ASC
SQL;
		  $results = $this->_db->fetch($sql, array(":id" => $this->getId()));

		  foreach ($results as $result) {
			  $childInfo[$result["id"]] = $result["title"];
		  }
	  }
    else {
			$childInfo["1"] = "Dàin do Eimhir";
      $childInfo["2"] = "Sgeulachdan Dhòmhnaill Alasdair";
      // need to insert SQL call here

		}
		return $childInfo;
	}





	public function getWriters() {
		return $this->_writers;
	}

	public function getFilepath() {
		return $this->_filepath;
	}

	public function getTransformedText() {
		$this->_transformedText = $this->_applyXSLT();
		return $this->_transformedText;
	}
}

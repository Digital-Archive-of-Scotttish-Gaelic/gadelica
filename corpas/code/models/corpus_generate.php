<?php

namespace models;

class corpus_generate
{

  private $_id; // the id number for the text in the corpus (obligatory)
  private $_filepaths = []; // an array of XML filepaths
  private $_lexemes = []; // an array of headwords

	private $_db;   // an instance of models\database

	public function __construct($id) {
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_id = $id;
		$this->_filepaths = $this->_getFilepaths($id);
    $this->_lexemes = $this->_getLexemes();
	}

  private function _getFilepaths($id) {
    $sql = <<<SQL
      SELECT filepath
        FROM text
        WHERE id = :id
SQL;
    $results = $this->_db->fetch($sql, array(":id" => $id));
    $textData = $results[0];
    if ($textData["filepath"]) {
      return [$textData["filepath"]];
    }
    else if ($id=="0") {
      $oot = [];
      $sql = <<<SQL
        SELECT filepath
          FROM text
SQL;
      $results = $this->_db->fetch($sql, array());
      foreach ($results as $nextResult) {
        if ($nextResult["filepath"]) {
          $oot[] = $nextResult["filepath"];
        }
      }
      return $oot;
    }
    else {
      $oot = [];
      $sql = <<<SQL
        SELECT id
          FROM text
          WHERE partOf = :id
SQL;
      $results = $this->_db->fetch($sql, array(":id" => $id));
      foreach ($results as $nextResult) {
        $oot2 = array_merge($oot,$this->_getFilepaths($nextResult["id"]));
        $oot = $oot2;
      }
      return $oot;
    }
  }

  private function _getLexemes() {
    $oot = [];
    foreach ($this->_filepaths as $nextFilepath) {
      $text = new \SimpleXMLElement("../xml/" . $nextFilepath, 0, true);
      $text->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
  		foreach ($text->xpath("//dasg:w") as $nextWord) {
        $lemma = (string)$nextWord["lemma"];
        $pos = (string)$nextWord["pos"];
        if (substr($pos,0,1)=='n') {
          $oot[] = $lemma . '|' . 'n';
        }
        else if (substr($pos,0,1)=='v' || substr($pos,0,1)=='V') {
          $oot[] = $lemma . '|' . 'v';
        }


      }
    }
    $oot2 = array_unique($oot);
    usort($oot2,'models\functions::gdSort');
    return $oot2;
  }


	/**
	 * Populates the object from the DB
	 */
	private function _load() {
		$sql = <<<SQL
			SELECT title, partOf, filepath, date, level, notes
				FROM text
				WHERE id = :id
SQL;
		$results = $this->_db->fetch($sql, array(":id" => $this->getId()));
		$textData = $results[0];
		$this->_setTitle($textData["title"]);
		if ($parentTextId = $textData["partOf"]) {    // create a parent text
			$this->_setParentText($parentTextId);
		}
		else {
			$this->_setParentText("0"); // the root corpus
		}
		if ($filepath = $textData["filepath"]) {
			$this->_setFilepath($filepath);
		}
		if ($date = $textData["date"]) {
			$this->_setDate($date);
		}
		if ($level = $textData["level"]) {
			$this->_setLevel($level);
		}
		if ($notes = $textData["notes"]) {
			$this->_setNotes($notes);
		}
		$this->_setWriters();
	}

	// SETTERS

	// GETTERS

	public function getId() {
		return $this->_id;
	}

  public function getFilepaths() {
    return $this->_filepaths;
  }

  public function getLexemes() {
    return $this->_lexemes;
  }

	/**
	 * Get child text info (on the fly to cut down on memory overhead)
	 * @return array of associative info ("id" => "title")
	 */
	public function getChildTextsInfo() {
		$childTextsInfo = array();
		$sql = <<<SQL
			SELECT id, title FROM text WHERE partOf = :id ORDER BY id ASC
SQL;
		$results = $this->_db->fetch($sql, array(":id" => $this->getId()));
		foreach ($results as $result) {
			$childTextsInfo[$result["id"]] = $result["title"];
		}
		return $childTextsInfo;
	}


}

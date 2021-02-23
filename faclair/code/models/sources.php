<?php

namespace models;

class sources {

  private $_sources = array(); // an array of source ids
  private $_db;   // an instance of models\database

	public function __construct() {
		$this->_db = isset($this->_db) ? $this->_db : new database();
		$this->_load();
	}

  private function _load() {
    $query = <<<SQL
    	SELECT DISTINCT `source`
    		FROM `lexemes`
    		ORDER BY `source`
SQL;
    $results = $this->_db->fetch($query);
		foreach ($results as $nextResult) {
			$this->_sources[] = $nextResult["source"];
		}
	}

  public function getSources() {
    return $this->_sources;
	}

  public static function getShortRef($id) {
    switch ($id) {
    	case "1":
    		return 'Eaglais na h-Alba';
    		break;
    	case "22":
    		return 'Dwelly';
    		break;
    	case "23":
    		return 'DASG supplement';
    		break;
    	default:
    		return '[unknown]';
    }
	}

  public static function getRef($id) {
    switch ($id) {
    	case "1":
    		return 'Eaglais na h-Alba – <em>Handbook of Biblical and Ecclesiastical Gaelic</em>';
    		break;
    	case "22":
    		return 'Dwelly – <em>Faclair Gàidhlig gu Beurla le Dealbhan</em>';
    		break;
    	case "23":
    		return 'DASG supplement';
    		break;
    	default:
    		return '[unknown]';
    }
	}


  public static function getEmoji($id) {
    switch ($id) {
      case "1":
        return '⛪️';
        break;
      case "22":
        return '🧩';
        break;
      case "23":
        return '📌';
        break;
      default:
        return '';
    }
  }

  public static function getExtLink($id) {
    switch ($id) {
    	case "1":
    		return 'https://www.churchofscotland.org.uk/__data/assets/pdf_file/0011/68708/ER-Gaelic-HANDBOOK-V5.pdf';
    		break;
    	default:
    		return '';
    }
	}

}

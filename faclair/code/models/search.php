<?php

namespace models;

class search {

  private $_search = ""; // the search term
  private $_entries_en = array(); // an array of hw-pos-sub(-en)(-althw)(-form) 4-tuples
  private $_entries_gd = array(); // an array of hw-pos-sub(-en)(-althw)(-form) 4-tuples
  private $_db;   // an instance of models\database

  public function __construct() {
    if (isset($_GET["search"])) {
      $this->_search = $_GET["search"];
      $this->_db = isset($this->_db) ? $this->_db : new database();
      $this->_load();
    }
  }

  private function _load() {
    $results = [];
    $results = $this->_englishExactSearch();
    $results1 = array_merge($results,$this->_englishPrefixSpaceSearch());
    $results2 = array_merge($results1,$this->_englishSuffixSpaceSearch());
    $results3 = array_merge($results2,$this->_englishInfixSpaceBothSearch());
    $results4 = array_merge($results3,$this->_englishPrefixNoSpaceSearch());
    $results5 = array_merge($results4,$this->_englishSuffixNoSpaceSearch());
    $results6 = array_merge($results5,$this->_englishInfixSpaceLeftSearch());
    $results7 = array_merge($results6,$this->_englishInfixSpaceRightSearch());
    // infix no space?
    $results = $results7;
		foreach ($results as $nextResult) {
			$this->_entries_en[] = explode('|',$nextResult);
		}
    $results = [];
    $results = $this->_gaelicExactHwSearch();
    $results1 = array_merge($results,$this->_gaelicExactFormSearch());
    $results2 = array_merge($results1,$this->_gaelicPrefixHwSpaceSearch());
    $results3 = array_merge($results2,$this->_gaelicSuffixHwSpaceSearch());
    $results4 = array_merge($results3,$this->_gaelicInfixHwSpaceBothSearch());
    $results5 = array_merge($results4,$this->_gaelicPrefixHwNoSpaceSearch());
    $results6 = array_merge($results5,$this->_gaelicSuffixHwNoSpaceSearch());
    $results7 = array_merge($results6,$this->_gaelicInfixHwSpaceLeftSearch());
    $results8 = array_merge($results7,$this->_gaelicInfixHwSpaceRightSearch());
    // GD forms as infixes etc??
    // GD lenition on suffixes and infixes??
    $results = $results8;
    foreach ($results as $nextResult) {
      $this->_entries_gd[] = explode('|',$nextResult);
    }
	}

  private function _englishExactSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` = :en
      ORDER BY `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _englishPrefixSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '^' . $this->_search . '[ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _englishSuffixSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '.*[ -]' . $this->_search . '$'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _englishInfixSpaceBothSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '.*[ -]' . $this->_search . '[ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _englishPrefixNoSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '^' . $this->_search . '[^ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _englishSuffixNoSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '.*[^ -]' . $this->_search . '$'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _englishInfixSpaceLeftSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '.*[ -]' . $this->_search . '[^ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _englishInfixSpaceRightSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, e.`en`
      FROM `lexemes` l
      JOIN `english` e ON l.`id` = e.`lexeme_id`
      WHERE e.`en` REGEXP :en
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":en" => '.*[^ -]' . $this->_search . '[ -].*'));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["en"];
    }
    return $oot;
  }

  private function _gaelicExactHwSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` = :gd
      ORDER BY `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _gaelicExactFormSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, f.`form`, f.`morph`
      FROM `lexemes` l
      JOIN `forms` f ON l.`id` = f.`lexeme_id`
      WHERE f.`form` = :gd
      ORDER BY `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $oot[] = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|' . $nextResult["form"] . ' <em>' . $nextResult["morph"] . '</em>';
    }
    return $oot;
  }

  private function _gaelicPrefixHwSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` LIKE :gd1 OR `hw` LIKE :gd2
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd1" => $this->_search . ' %', ":gd2" => $this->_search . '-%'));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _gaelicSuffixHwSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` LIKE :gd1 OR `hw` LIKE :gd2
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd1" => '% ' . $this->_search, ":gd2" => '%-' . $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _gaelicInfixHwSpaceBothSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` LIKE :gd1 OR `hw` LIKE :gd2 OR `hw` LIKE :gd3 OR `hw` LIKE :gd4
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql,
      array(":gd1" => '% ' . $this->_search . ' %',
            ":gd2" => '%-' . $this->_search . ' %',
            ":gd3" => '% ' . $this->_search . '-%',
            ":gd4" => '%-' . $this->_search . '-%'
          ));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _gaelicPrefixHwNoSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` LIKE :gd1 AND `hw` NOT LIKE :gd2 AND `hw` NOT LIKE :gd3 AND `hw` != :gd4
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd1" => $this->_search . '%',
                                             ":gd2" => $this->_search . ' %',
                                             ":gd3" => $this->_search . '-%',
                                             ":gd4" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _gaelicSuffixHwNoSpaceSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE `hw` LIKE :gd1 AND `hw` NOT LIKE :gd2 AND `hw` NOT LIKE :gd3 AND `hw` != :gd4
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd1" => '%' . $this->_search,
                                             ":gd2" => '% ' . $this->_search,
                                             ":gd3" => '%-' . $this->_search,
                                             ":gd4" => $this->_search));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _gaelicInfixHwSpaceLeftSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE (`hw` LIKE :gd1 OR `hw` LIKE :gd2) AND `hw` NOT LIKE :gd3 AND `hw` NOT LIKE :gd4 AND `hw` NOT LIKE :gd5 AND `hw` NOT LIKE :gd6 AND `hw` NOT LIKE :gd7
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd1" => '% ' . $this->_search . '%',
                                             ":gd2" => '%-' . $this->_search . '%',
                                             ":gd3" => '% ' . $this->_search . ' %',
                                             ":gd4" => '% ' . $this->_search . '-%',
                                             ":gd5" => '%-' . $this->_search . ' %',
                                             ":gd6" => '%-' . $this->_search . '-%',
                                             ":gd7" => '%' . $this->_search
                                           ));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  private function _gaelicInfixHwSpaceRightSearch() {
    $sql = <<<SQL
    SELECT DISTINCT `m-hw`, `m-pos`, `m-sub`, `hw`
      FROM `lexemes`
      WHERE (`hw` LIKE :gd1 OR `hw` LIKE :gd2) AND `hw` NOT LIKE :gd3 AND `hw` NOT LIKE :gd4 AND `hw` NOT LIKE :gd5 AND `hw` NOT LIKE :gd6 AND `hw` NOT LIKE :gd7
      ORDER BY LENGTH(`m-hw`), `m-hw`
SQL;
    $results = $this->_db->fetch($sql, array(":gd1" => '%' . $this->_search . ' %',
                                             ":gd2" => '%' . $this->_search . '-%',
                                             ":gd3" => '% ' . $this->_search . ' %',
                                             ":gd4" => '% ' . $this->_search . '-%',
                                             ":gd5" => '%-' . $this->_search . ' %',
                                             ":gd6" => '%-' . $this->_search . '-%',
                                             ":gd7" => $this->_search . '%'
                                           ));
    $oot = [];
    foreach ($results as $nextResult) {
      $str = $nextResult["m-hw"] . '|' . $nextResult["m-pos"] . '|'. $nextResult["m-sub"] . '|';
      if ($nextResult["hw"]!=$nextResult["m-hw"]) { $str .= $nextResult["hw"]; }
      $oot[] = $str;
    }
    return $oot;
  }

  public function getSearch() {
    return $this->_search;
  }

  public function getEntriesEn() {
    return $this->_entries_en;
	}

  public function getEntriesGd() {
    return $this->_entries_gd;
	}

}

<?php


class SearchController
{
  private $_db, $_dbResults, $_resultCount;

  public function __construct() {
    if (!isset($this->_db)) {
      $this->_db = new Database();
    }
    $_GET["pp"] = isset($_GET["pp"]) ? $_GET["pp"] : 10;
    $_GET["page"] = isset($_GET["page"]) ? $_GET["page"] : 1;

    if (!isset($_REQUEST["action"])) {
      $_REQUEST["action"] = "newSearch";
    }

    switch ($_REQUEST["action"]) {
      case "newSearch":
        $searchView = new SearchView();
        $searchView->writeSearchForm();
        break;
      case "runSearch":
        $this->_resultCount = $this->_getDBSearchResultsTotal($_GET);
        $this->_dbResults = $this->_getDBSearchResults($_GET);
        $results = $this->getFileSearchResults();
        $searchView = new SearchView();
        $searchView->writeSearchResults($results, $this->_resultCount);
        break;
    }
  }

  /*
   * Takes an array of database results and searches through the XML corpus for matches
   */
  public function getFileSearchResults() {
    $fileResults = array();
    $i = 0;
    foreach ($this->_dbResults as $result) {
      $id = trim($result["id"]);
      $fileResults[$i]["id"] = $id;
      $fileResults[$i]["filename"] = $result["filename"];
      $i++;
    }
    return $fileResults;
  }

  private function _getDBSearchResults($params) {
    $search = $params["search"];
   // $search = " " . $search;  //temp hack to allow for malformed CSV input
    $perpage = $params["pp"];
    $pagenum = $params["page"];
    $offset = $pagenum == 1 ? 0 : ($perpage * $pagenum) - $perpage;
    /* wordform search */
    if ($params["mode"] == "wordform") {
      $sql = $this->_getWordformQuerySql($params);
      $sql .= <<<SQL
        ORDER BY filename, id
        LIMIT {$perpage} OFFSET {$offset}
SQL;
      $this->_dbResults = $this->_db->fetch($sql, array($search));
      return $this->_dbResults;
    }

    /* lemma search */
    $sql = <<<SQL
  SELECT filename, id, wordform FROM lemmas
    WHERE lemma = ?
    ORDER BY filename, id
    LIMIT {$perpage} OFFSET {$offset}
SQL;
    $this->_dbResults = $this->_db->fetch($sql, array($search));
    return $this->_dbResults;
  }

  private function _getWordformQuerySql($params) {
    $search = trim($params["search"]);  //temp fix for malformed CSV input
    /*
     * SB: note on the following line for $collate: if performance becomes an issue, creating a duplicate
     * wordform column with a different collation might be the way to go
     */
   // $collate = ($params["accent"] != "sensitive") ? "COLLATE utf8_general_ci" : "";  //accent sensitive check
    $whereClause = ($params["case"] != "sensitive") ? "LOWER (wordform) = LOWER (?)" : "wordform = ?";  //case sensitive check
    if ($params["lenition"] != "sensitive") {
      $binary = ($params["case"] != "sensitive") ? "" : "BINARY ";
      $whereClause = "wordform REGEXP {$binary}'{$this->_getLenitionRegEx($search)}'";
    }
    $sql = <<<SQL
        SELECT filename, id FROM lemmas
          WHERE {$whereClause} {$collate}
SQL;
      return $sql;
  }

  private function _getLenitionRegEx($string) {
    $start = (mb_substr($string, 1, 1) == "h") ? 2 : 1;
    $regEx = mb_substr($string, 0, 1) . "h?" . mb_substr($string, $start);
    $regEx .= "$";
    return $regEx;
  }

  /*
   * Query to get the size of the complete result set
   * Return int: count of the size of the set
   */
  private function _getDBSearchResultsTotal($params)
  {
    $params["search"] = ' ' . $params["search"];   //temp hack for malformed CSV input
    if ($params["mode"] == "lemma") { //lemma
      $collate = ($params["accent"] != "sensitive") ? "COLLATE utf8_general_ci" : "";  //accent sensitive check
      $sql = <<<SQL
        SELECT wordform FROM lemmas WHERE lemma = ? {$collate}
SQL;
    } else {  //wordform
      $sql = $this->_getWordformQuerySql($params);
    }
    $results = $this->_db->fetch($sql, array($params["search"]));
    echo "<h1>" . count($results) . "</h1>";
    return count($results);

/*
  previous code ...
*/
      /*
      $collate = ($params["accent"] == "sensitive") ? "COLLATE utf8_bin" : "";  //accent sensitive check
      $column = $params["mode"] == "wordform" ? "wordform" : "lemma";
      $params["search"] = $column == "wordform" ? " " . $params["search"] : $params["search"];  //temp hack for CSV issue
      $sql = <<<SQL
  SELECT wordform FROM lemmas WHERE {$column} = ? {$collate}
SQL;
      $results = $this->_db->fetch($sql, array($params["search"]));
      return count($results);
    }
      */
  }
}

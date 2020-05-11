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
    $perpage = $params["pp"];
    $pagenum = $params["page"];
    $offset = $pagenum == 1 ? 0 : ($perpage * $pagenum) - $perpage;
    $whereClause = ($params["case"] != "sensitive") ? "LOWER (wordform) = LOWER (?)" : "wordform = ?";  //accent sensitive check

    /* wordform search */
    if ($params["mode"] == "wordform") {
      $search = ' ' . $search; //temp hack for malformed CSV input
      $sql = <<<SQL
  SELECT filename, id FROM lemmas
    WHERE {$whereClause}
    ORDER BY filename, id
    LIMIT {$perpage} OFFSET {$offset}
SQL;

      echo $sql;
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

  /*
   * Query to get the size of the complete result set
   * Return int: count of the size of the set
   */
  private function _getDBSearchResultsTotal($params) {
    $collate = ($params["accent"]== "sensitive") ? "COLLATE utf8_bin" : "";  //accent sensitive check
    $column = $params["mode"] == "wordform" ? "wordform" : "lemma";
    $params["search"] = $column == "wordform" ? " " . $params["search"] : $params["search"];  //temp hack for CSV issue
    $sql = <<<SQL
  SELECT wordform FROM lemmas WHERE {$column} = ? {$collate}
SQL;
    $results = $this->_db->fetch($sql, array($params["search"]));
    return count($results);
  }
}

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
        $this->_resultCount = $this->_getDBSearchResultsTotal($_GET["search"]);
        $this->_dbResults = $this->_getDBSearchResults($_GET["search"], $_GET["pp"], $_GET["page"]);
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

  private function _getDBSearchResults($search, $perpage, $pagenum) {
    $offset = $pagenum == 1 ? 0 : ($perpage * $pagenum) - $perpage;
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
  private function _getDBSearchResultsTotal($search) {
    $sql = <<<SQL
  SELECT wordform FROM lemmas WHERE lemma = ?
SQL;
    $results = $this->_db->fetch($sql, array($search));
    return count($results);
  }
}

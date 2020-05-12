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
        $searchView = new SearchView();
        $this->_resultCount = !isset($_GET["hits"]) ? $this->_getDBSearchResultsTotal($_GET) : $_GET["hits"];
        $searchView->setHits($this->_resultCount);
        $this->_dbResults = $this->_getDBSearchResults($_GET);
        $results = $this->getFileSearchResults();
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

    /* wordform search */
    if ($params["mode"] == "wordform") {
      $query = $this->_getWordformQuery($params);
      $sql = $query["sql"];
      $sql .= <<<SQL
            ORDER BY filename, id
            LIMIT {$perpage} OFFSET {$offset}
SQL;
      $this->_dbResults = $this->_db->fetch($sql, array($query["search"]));
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
   * Form and return the query required for a wordform search
   * Returns an associative array with the SQL and search term
   */
  private function _getWordformQuery($params) {
    $search = $params["search"];
    if ($params["accent"] != "sensitive") {
      $search = Functions::getAccentInsensitive($search, $params["case"] == "sensitive");
    }
    if ($params["lenition"] != "sensitive") {
      $search = Functions::getLenited($search);
    }
    $whereClause = "";
    $search = "[[:<:]]" . $search . "[[:>:]]";  //word boundary
    if ($params["case"] == "sensitive") {   //case sensitive
      $whereClause .= "wordform_bin REGEXP ?";
    } else {                              //case insensitive
      $whereClause .= "wordform REGEXP ?";
    }
    $sql = <<<SQL
        SELECT filename, id FROM lemmas
          WHERE {$whereClause}
SQL;
      return array("sql" => $sql, "search" => $search);
  }

  /*
   * Query to get the size of the complete result set
   * Return int: count of the size of the set
   */
  private function _getDBSearchResultsTotal($params)
  {
    if ($params["mode"] == "headword") {    //lemma
      $query["search"] = $params["search"];
      $query["sql"] = <<<SQL
        SELECT wordform FROM lemmas WHERE lemma = ? 
SQL;
    } else {                                //wordform
      $query = $this->_getWordformQuery($params);
    }
    $results = $this->_db->fetch($query["sql"], array($query["search"]));
    return count($results);
  }
}

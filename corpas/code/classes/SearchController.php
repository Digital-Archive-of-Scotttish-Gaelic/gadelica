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
        $chunk = ($searchView->getView() == "dictionary") ? false : true;
        $this->_dbResults = $this->_getDBSearchResults($_GET, $chunk);
        $results = ($_GET["view"] == "corpus") ? $this->getFileSearchResults() : $this->_dbResults;
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
      $id = $result["id"];
      $fileResults[$i]["id"] = $id;
      $fileResults[$i]["lemma"] = $result["lemma"];
      $fileResults[$i]["pos"] = $result["pos"];
      $fileResults[$i]["date_of_lang"] = $result["date_of_lang"];
      $fileResults[$i]["filename"] = $result["filename"];
      $i++;
    }
    return $fileResults;
  }

  private function _getDBSearchResults($params, $chunk = true) {
   // $search = $params["search"];
    $perpage = $params["pp"];
    $pagenum = $params["page"];
    $offset = $pagenum == 1 ? 0 : ($perpage * $pagenum) - $perpage;

    return array_slice($_SESSION["results"], $offset, $perpage);
/*
    switch ($params["date"]) {
      case "random":
        $orderBy = "RAND()";
        break;
      case "asc":
        $orderBy = "date_of_lang ASC";
        break;
      case "desc":
        $orderBy = "date_of_lang DESC";
        break;
      default:
        $orderBy = "filename, id";
    }
    // wordform search
    $limit = ($chunk == true) ? "LIMIT {$perpage} OFFSET {$offset}" : "";
    if ($params["mode"] == "wordform") {
      $query = $this->_getWordformQuery($params);
      $sql = $query["sql"];
      $sql .= <<<SQL
            ORDER BY {$orderBy}
            {$limit}
SQL;
      $this->_dbResults = $this->_db->fetch($sql, array($query["search"]));
      return $this->_dbResults;
    }

    //lemma search
    $sql = <<<SQL
        SELECT filename, id, wordform, pos, lemma, date_of_lang FROM lemmas
            WHERE lemma = ?
            ORDER BY filename, id
            {$limit}
SQL;
    $this->_dbResults = $this->_db->fetch($sql, array($search));
    return $this->_dbResults; */
  }

  /*
   * Form and return the query required for a wordform search
   * Returns an associative array with the SQL and search term
   */
  private function _getWordformQuery($params) {
    $search = $params["search"];
    $searchPrefix = "[[:<:]]";  //default to word boundary at start
    if ($params["accent"] != "sensitive") {
      $search = Functions::getAccentInsensitive($search, $params["case"] == "sensitive");
    }
    if ($params["lenition"] != "sensitive") {
      $search = Functions::getLenited($search);
      $search = Functions::addMutations($search);
    } else {
      //deal with h-, n-, t-
      $searchPrefix = "^";  //don't use word boundary at start of search, but start of string instead
    }
    $whereClause = "";
    $search = $searchPrefix . $search . "[[:>:]]";  //word boundary
    if ($params["case"] == "sensitive") {   //case sensitive
      $whereClause .= "wordform_bin REGEXP ?";
    } else {                              //case insensitive
      $whereClause .= "wordform REGEXP ?";
    }
    $selectFields =  "lemma, filename, id, wordform, pos, date_of_lang";
    $sql = <<<SQL
        SELECT {$selectFields} FROM lemmas
          WHERE {$whereClause}
SQL;
    return array("sql" => $sql, "search" => $search);
  }

  /*
   * Query to get the size of the complete result set
   * Return int: count of the size of the set
   */
  private function _getDBSearchResultsTotal($params) {
    switch ($params["date"]) {
      case "random":
        $orderBy = "RAND()";
        break;
      case "asc":
        $orderBy = "date_of_lang ASC";
        break;
      case "desc":
        $orderBy = "date_of_lang DESC";
        break;
      default:
        $orderBy = "filename, id";
    }
    if ($params["mode"] == "headword") {    //lemma
      $query["search"] = $params["search"];
      $query["sql"] = <<<SQL
        SELECT filename, id, wordform, pos, lemma, date_of_lang FROM lemmas
            WHERE lemma = ?
SQL;
    } else {                                //wordform
      $query = $this->_getWordformQuery($params);
    }
    $query["sql"] .= $this->_getDateWhereClause($params);
    $query["sql"] .= <<<SQL
        ORDER BY {$orderBy}
SQL;
    $_SESSION["results"] = $this->_db->fetch($query["sql"], array($query["search"]));
    return count($_SESSION["results"]);
  }

  private function _getDateWhereClause($params) {
    $whereClause = " AND date_of_lang >= '1800' AND date_of_lang <= '1999' ";
    return $whereClause;
  }
}

<?php


class SearchController
{
  private $_db;

  public function __construct() {
    if (!isset($this->_db)) {
      $this->_db = new Database();
    }

    if (!isset($_REQUEST["action"])) {
      $_REQUEST["action"] = "newSearch";
    }

    switch ($_REQUEST["action"]) {
      case "newSearch":
        $searchView = new SearchView();
        $searchView->writeSearchForm();
        break;
      case "runSearch":
        $results = $this->getSearchResults($_REQUEST["search"]);
        $searchView = new SearchView();
        $searchView->writeSearchResults($results);
        break;
    }
  }

  public function getSearchResults($search) {
    $sql = <<<SQL
  SELECT filename, id, wordform FROM lemmas WHERE lemma = ?
SQL;
    $results = $this->_db->fetch($sql, array($search));
    return $results;
  }

}
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
        $results = $this->getFileSearchResults();
        $searchView = new SearchView();
        $searchView->writeSearchResults($results);
        break;
    }
  }

  /*
   * Takes an array of database results and searches through the XML corpus for matches
   */
  public function getFileSearchResults() {
    $dbResults = $this->getDBSearchResults($_REQUEST["search"]);
    $fileResults = array();
    $currentFile = $xml = "";
    $i = 0;
    foreach ($dbResults as $result) {
      //check for next filename
      if ($currentFile != $result["filename"]) {

        echo "<br>{$currentFile}";
        $currentFile = trim($result["filename"]);
        $xml = simplexml_load_file(INPUT_FILEPATH . $currentFile);
        $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
      }
      $id = trim($result["id"]);
      $xpath = <<<XPATH
        //dasg:w[@id='{$id}']
XPATH;
      $word = $xml->xpath($xpath);
      $fileResults[$i]["wordform"] = $word[0];
      $fileResults[$i]["id"] = $id;
      $fileResults[$i]["filename"] = $result["filename"];
      $i++;
    }
    return $fileResults;
  }

  public function getDBSearchResults($search) {
    $sql = <<<SQL
  SELECT filename, id, wordform FROM lemmas WHERE lemma = ?
    ORDER BY filename, id 
SQL;
    $results = $this->_db->fetch($sql, array($search));
    return $results;
  }

}
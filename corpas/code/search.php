<?php

//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

require_once "includes/htmlHeader.php";

$controller = new SearchController();

/*
$results = array();
//
$hitCount = 0;  $tempLimit = 50;//temp counter for testing purposes
//
foreach (new DirectoryIterator(INPUT_FILEPATH) as $fileinfo) {
  if ($fileinfo->isDot()) continue;
  $filename = $fileinfo->getFilename();
  $xml = simplexml_load_file(INPUT_FILEPATH . '/' . $filename);
  $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
  $words = $xml->xpath("//dasg:w[contains(@lemma, 'craobh')]");
  $follContext = $xml->xpath("//dasg:w[contains(@lemma, 'craobh')]/following-sibling::dasg:w[1]");
  foreach ($words as $word) {
//print_r($word);
    $results[$hitCount]["word"] = $word[0];
//echo count($follContext);
    $following = implode(' ', $follContext);
    $results[$hitCount]["following"] = $following;
    $hitCount++;    //temp
  }
  if ($hitCount >= $tempLimit) {break;} //temp
}
*/

require_once "includes/htmlFooter.php";

/*

MM documentation
----------------

Input parameters –

action = newSearch

action = runSearch
  & search = craobh
  & mode = headword | wordform
  & view = corpus | dictionary
  & date = off | random | asc | desc
  & selecteddates = | 1900-1999
  & submit =

  & pp = 10
  & page = 2 | 3 | ...
  & case = | sensitive
  & accent = | sensitive
  & lenition = | sensitive
  & hits = 39

*/

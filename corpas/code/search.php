<?php

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

/* Some suggestions for next steps:
1. Output results as rows in a Bootstrap table (see Bootstrap online docs for details)
2. Three cols to start with: previous context, word, following context
3. Context defined as five elements from the set: dasg:w, dasg:pc, dasg:o
*/

require_once "includes/htmlFooter.php";

<?php
/* converts the corpus into a csv file for import to lemma database */

//create ass array from filenames to years
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?xml ?date
WHERE
{
  ?id :xml ?xml .
  {
    { ?id :internalDate ?date . }
    UNION
    { ?id dc:isPartOf ?id2 . ?id2 :internalDate ?date . }
    UNION
    { ?id dc:isPartOf ?id2 . ?id2 dc:isPartOf ?id3 . ?id3 :internalDate ?date . }
  }
}
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Corpus?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/corpas/code/mm_utilities') {
  $url = 'http://localhost:3030/Corpus?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
//echo $json;
$results = json_decode($json,false)->results->bindings;
$dates = [];
foreach ($results as $nextResult) {
  $nextFile = $nextResult->xml->value;
  $nextDate = $nextResult->date->value;
  $dates[$nextFile] = $nextDate;
}
/*
foreach ($dates as $key => $value) {
  echo $key . ' ' . $value . PHP_EOL;
}
*/


$it = new RecursiveDirectoryIterator('../../xml');
foreach (new RecursiveIteratorIterator($it) as $nextFile) {
  if ($nextFile->getExtension()=='xml') {
    $xml = simplexml_load_file($nextFile);
    $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    foreach ($xml->xpath("//dasg:w") as $nextWord) {
      $lemma = (string)$nextWord['lemma'];
      if ($lemma && !strpos($lemma,' ')) { // ann an ???
        echo $lemma . ',';
        $filename = substr($nextFile,10);
        echo $filename . ',';
        echo $nextWord['id'] . ',';
        echo $nextWord . ',';
        echo $nextWord . ',';
        echo $nextWord['pos'] . ',';
        if ($dates[$filename]) { echo $dates[$filename]; }
        else { echo '9999'; }
        echo PHP_EOL;
      }
    }
  }
}




?>

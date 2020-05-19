<?php
/* converts the corpus into a csv file for import to lemma database */

$it = new RecursiveDirectoryIterator('../../xml');

//$words = [];
foreach (new RecursiveIteratorIterator($it) as $nextFile) {
  if ($nextFile->getExtension()=='xml') {
    $xml = simplexml_load_file($nextFile);
    $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    foreach ($xml->xpath("//dasg:w") as $nextWord) {
      $lemma = (string)$nextWord['lemma'];
      if ($lemma && !strpos($lemma,' ')) { // ann an ???
        echo $lemma . ',';
        echo substr($nextFile,10) . ',';
        echo $nextWord['id'] . ',';
        echo $nextWord . ',';
        echo $nextWord . ',';
        echo $nextWord['pos'] . ',';
        echo rand(1800,1999);
        echo PHP_EOL;
      }
    }
  }
}
/*
$lemmas = [];
foreach ($words as $id => $lemma) {
  $lemmas[$lemma][] = $id;
}
ksort($lemmas);

foreach ($lemmas as $lemma => $ids) {
  echo $lemma . ', ' . implode(', ',$ids) . PHP_EOL;
}
echo count($lemmas) . PHP_EOL;
*/



?>

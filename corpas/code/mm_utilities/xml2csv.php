<?php

$it = new RecursiveDirectoryIterator('../../xml');

$words = [];
foreach (new RecursiveIteratorIterator($it) as $nextFile) {
  if ($nextFile->getExtension()=='xml') {
    $xml = simplexml_load_file($nextFile);
    $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    foreach ($xml->xpath("//dasg:w") as $nextWord) {
      $id = substr($nextFile,10) . '#' . $nextWord['id'];
      $lemma = (string)$nextWord['lemma'];
      if ($lemma) {$words[$id] = $lemma;}
    }
  }
}
$lemmas = [];
foreach ($words as $id => $lemma) {
  $lemmas[$lemma][] = $id;
}
ksort($lemmas);


// display
foreach ($lemmas as $lemma => $ids) {
  echo $lemma . ', ' . implode(', ',$ids) . PHP_EOL;
}
echo count($lemmas) . PHP_EOL;




?>

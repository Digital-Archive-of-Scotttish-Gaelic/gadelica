<?php

$it = new RecursiveDirectoryIterator('../../xml/');
$taggedWords = 0;
$untaggedWords = 0;
foreach (new RecursiveIteratorIterator($it) as $nextFile) {
  if ($nextFile->getExtension()=='xml') {
    $xml = simplexml_load_file($nextFile);
    $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    $status = $xml->xpath("/dasg:text/@status")[0];
    if ($status == 'tagged') {
      $taggedWords += count($xml->xpath("//dasg:w"));
    }
    else {
      $untaggedWords += count($xml->xpath("//dasg:w"));
    }
  }
}
echo $taggedWords . ' tagged words' . PHP_EOL;
echo $untaggedWords . ' untagged words' . PHP_EOL;

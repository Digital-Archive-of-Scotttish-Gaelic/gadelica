<?php

require_once "../includes/include.php";
require_once "../classes/Functions.php";

$it = new RecursiveDirectoryIterator("../../xml/");
$words = [];
foreach (new RecursiveIteratorIterator($it) as $nextFile) {
  if ($nextFile->getExtension()=='xml') {
    $xml = simplexml_load_file($nextFile);
    $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    $status = $xml->xpath("/dasg:text/@status")[0];
    if ($status == 'tagged') {
      foreach ($xml->xpath("//dasg:w") as $nextWord) {
        $form = $nextWord;
        $lemma = (string)$nextWord['lemma'];
        if ($lemma=='') { $lemma = $form; }
        if (strtolower($lemma[0]) == $lemma[0]) { $form = strtolower($form); }
        $pos = (string)$nextWord['pos'];
        $words[] = $form . '|' . $lemma . '|' . $pos;
      }
    }
  }
}
usort($words,'Functions::gdSort');
$lexicon = [];
foreach ($words as $nextWord) {
  if ($lexicon[$nextWord]) {
    $lexicon[$nextWord]++;
  }
  else {
    $lexicon[$nextWord] = 1;
  }
}
$lexicon2 = [];
foreach ($lexicon as $nextWord => $nextCount) {
  $bits = explode('|',$nextWord);
  if ($lexicon2[$bits[0]]) {
    $bits2 = explode('|',$lexicon2[$bits[0]]);
    if ($nextCount > $bits2[2]) {
      $lexicon2[$bits[0]] = $bits[1] . '|' . $bits[2] . '|' . $nextCount;
    }
  }
  else {
    $lexicon2[$bits[0]] = $bits[1] . '|' . $bits[2] . '|' . $nextCount;
  }
}

//open files and tag status="raw"

foreach (new RecursiveIteratorIterator($it) as $nextFile) {
  if ($nextFile->getExtension()=='xml') {
    $xml = simplexml_load_file($nextFile);
    $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    $status = $xml->xpath("/dasg:text/@status")[0];
    if ($status == 'raw') {
      foreach ($xml->xpath("//dasg:w") as $nextWord) {
        if ($lexicon2[(string)$nextWord]) {
          $bits = explode('|',$lexicon2[(string)$nextWord]);
          $nextWord['lemma'] = $bits[0];
          if ($bits[1]!='') {
            $nextWord['pos'] = $bits[1];
          }
          else {
            $nextWord['pos'] = False;
          }
        }
        else if ($lexicon2[strtolower((string)$nextWord)]) {
          $bits = explode('|',$lexicon2[strtolower((string)$nextWord)]);
          $nextWord['lemma'] = $bits[0];
          if ($bits[1]!='') {
            $nextWord['pos'] = $bits[1];
          }
          else {
            $nextWord['pos'] = False;
          }
        }
        else if (substr((string)$nextWord,1,1)==='h') {
          $delenited = substr((string)$nextWord,0,1) . substr((string)$nextWord,2);
          if ($lexicon2[$delenited]) {
            $bits = explode('|',$lexicon2[$delenited]);
            $nextWord['lemma'] = $bits[0];
            if ($bits[1]!='') {
              $nextWord['pos'] = $bits[1];
            }
            else {
              $nextWord['pos'] = False;
            }
          }
        }
      }
      $xml->asXML($nextFile);
    }
  }
}

/*
foreach ($lexicon2 as $nextForm => $nextTag) {
  echo $nextForm . ' ' . $nextTag . PHP_EOL;
}
*/



?>

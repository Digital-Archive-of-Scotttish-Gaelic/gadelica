<?php

require_once "includes/htmlHeader.php";

$it = new RecursiveDirectoryIterator(INPUT_FILEPATH);

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
        $pos = (string)$nextWord['pos'];

        echo $form . '|' . $lemma . '|' . $pos . '<br/>';
        /*
        $lemma = (string)$nextWord['lemma'];
        if ($lemma && !strpos($lemma,' ')) {
          echo $lemma . ', ';
          echo substr($nextFile,10) . ', ';
          echo $nextWord['id'] . ', ';
          echo $nextWord;
          echo PHP_EOL;
        }
        */

      }
    }

  }
}

require_once "includes/htmlFooter.php";

?>

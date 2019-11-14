<?php

$files = scandir('.');
foreach ($files as $nextFile) {
  if (strpos($nextFile, '.html')) {
    echo $nextFile . PHP_EOL;
    $txt = file_get_contents($nextFile);
    $start = strpos($txt, '<span id="lblGaelic">') + 21;
    $end = strpos($txt, '</span>', $start);
    $hw = substr($txt, $start, $end-$start);
    echo $hw . PHP_EOL;
    $start = strpos($txt, '<span id="lblEnglish">') + 22;
    $end = strpos($txt, '</span>', $start);
    $desc = substr($txt, $start, $end-$start);
    echo $desc . PHP_EOL;
    if (strpos($desc,'i>a. </i>') || strpos($desc,'i>a </i>') || strpos($desc,'i>a.</i>') || strpos($desc,'i> a</i>')) {
      echo ':Adjective' . PHP_EOL;
    }
    else if (strpos($desc,'i>sf')) {
      echo ':FeminineNoun' . PHP_EOL;
    }
    else if (strpos($desc,'i>sm</i>') || strpos($desc,'i>sm </i>')) {
      echo ':MasculineNoun' . PHP_EOL;
    }
    else if (strpos($desc,'i>adv </i>')) {
      echo ':Adverb' . PHP_EOL;
    }


    echo PHP_EOL;
  }
}

?>

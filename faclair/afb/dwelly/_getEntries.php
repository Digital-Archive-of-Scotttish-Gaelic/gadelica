<?php

$files = scandir('.');
foreach ($files as $nextFile) {
  if (strpos($nextFile, '.html')) {
    $txt = file_get_contents($nextFile);
    $start = strpos($txt, '<span id="lblGaelic">') + 21;
    $end = strpos($txt, '</span>', $start);
    $hw = substr($txt, $start, $end-$start);
    $start = strpos($txt, '<span id="lblEnglish">') + 22;
    $end = strpos($txt, '</span>', $start);
    $desc = substr($txt, $start, $end-$start);
    $desc = str_replace('"','',$desc);
    $start = strpos($desc,'<i>');
    $end = strpos($desc,'</i>',$start);
    $pos = substr($desc,$start+3,$end-$start-3);
    if ($pos=='a. ' || $pos=='a ' || $pos=='a.' || $pos==' a' || $pos=='a' || $pos==' a ') {
      $pos = ':Adjective';
    }
    else if ($pos=='sf' || $pos=='sf ' || $pos=='sf ind ' || $pos=='sf ind') {
      $pos = ':FeminineNoun';
    }
    else if ($pos=='sm' || $pos=='sm ' || $pos==' sm' || $pos=='sm ind ') {
      $pos = ':MasculineNoun';
    }
    else if ($pos=='s.' || $pos=='s' || $pos=='s ' || $pos=='s. ') {
      $pos = ':Noun';
    }
    else if ($pos=='npl ' || $pos=='spl ' || $pos=='n pl ' || $pos=='s.pl. ' || $pos=='pl ' || $pos=='pl.' || $pos=='pl. ') {
      $pos = ':PluralNoun';
    }
    else if ($pos=='vn ') {
      $pos = ':VerbalNoun';
    }
    else if ($pos=='va ' || $pos=='va' || $pos=='v. ' || $pos=='pr pt ' || $pos=='pr pt') {
      $pos = ':Verb';
    }
    else if ($pos=='adv ') {
      $pos = ':Adverb';
    }
    else {
      $pos = ':Undefined';
    }
    if (strpos($pos,'Noun')) {
      echo 'n:';
    }
    else if ($pos==':Adjective') {
      echo 'a:';
    }
    else if ($pos==':Verb') {
      echo 'v:';
    }
    else {
      echo 'o:';
    }
    $uri = $hw;
    $uri = str_replace(' ','_',$uri);
    $uri = str_replace('’','',$uri);
    $uri = str_replace('\'','',$uri);
    $uri = str_replace('(','',$uri);
    $uri = str_replace(')','',$uri);
    $uri = str_replace('?','',$uri);
    $uri = str_replace(',','',$uri);
    $uri = str_replace('!','',$uri);
    $uri = str_replace('-','_',$uri);
    $uri = str_replace('à','aa',$uri);
    $uri = str_replace('è','ee',$uri);
    $uri = str_replace('é','ee',$uri);
    $uri = str_replace('ì','ii',$uri);
    $uri = str_replace('ò','oo',$uri);
    $uri = str_replace('ó','oo',$uri);
    $uri = str_replace('ù','uu',$uri);
    $uri = str_replace('À','AA',$uri);
    $uri = str_replace('È','EE',$uri);
    $uri = str_replace('É','EE',$uri);
    $uri = str_replace('Ì','II',$uri);
    $uri = str_replace('Ò','OO',$uri);
    $uri = str_replace('Ó','OO',$uri);
    $uri = str_replace('Ù','UU',$uri);
    echo $uri . PHP_EOL;
    if ($pos!=':Undefined') {
      echo '  a ' . $pos . ' ;' . PHP_EOL;
    }
    echo '  rdfs:label "' . $hw . '" ;' . PHP_EOL ;
    echo '  :sense "' . $desc . '" ;' . PHP_EOL ;
    echo '  rdfs:comment "' . $desc . '" ;' . PHP_EOL ;
    echo '  rdfs:comment "ID: ' . substr($nextFile,0,33) . '" .' . PHP_EOL ;
    echo PHP_EOL;
  }
}

?>

<?php

$lx = new SimpleXMLElement("../FaclairInitialHeadwords.xml", 0, true);

foreach ($lx->entry as $nextEntry) {
  $u = 'n:' . $nextEntry->g;
  $u = str_replace(' ','_',$u);
  $u = str_replace('’','%27',$u);
  $u = str_replace('\'','%27',$u);
  $u = str_replace('(','%28',$u);
  $u = str_replace(')','%29',$u);
  $u = str_replace('?','%3F',$u);
  $u = str_replace('-','_',$u);
  $u = str_replace('à','aa',$u);
  $u = str_replace('è','ee',$u);
  $u = str_replace('ì','ii',$u);
  $u = str_replace('ò','oo',$u);
  $u = str_replace('ù','uu',$u);
  $u = str_replace('À','AA',$u);
  $u = str_replace('È','EE',$u);
  $u = str_replace('Ì','II',$u);
  $u = str_replace('Ò','OO',$u);
  $u = str_replace('Ù','UU',$u);
  echo $u . PHP_EOL;
  echo '  rdfs:label "' . $nextEntry->g . '" ;' . PHP_EOL;
  echo '  :sense';
  $senses = '';
  foreach ($nextEntry->e as $nextSense) {
    $senses .= ' "' . $nextSense . '" ,';
  }
  $senses = trim($senses,',');
  echo $senses . ';' . PHP_EOL;
  echo '  rdfs:comment "' . $nextEntry['source'];
  echo '" .' . PHP_EOL . PHP_EOL;
}


?>
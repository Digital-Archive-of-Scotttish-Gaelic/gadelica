<?php

$file = file_get_contents('snh.csv');
$array = explode(PHP_EOL, $file);

foreach ($array as $entry) {
  $tmp = explode(',', $entry);
  $hw = trim($tmp[1]);
  $uri = 'n:' . $hw;
  $uri = str_replace(' ','_',$uri);
  $uri = str_replace('’','%27',$uri);
  $uri = str_replace('\'','%27',$uri);
  $uri = str_replace('(','%28',$uri);
  $uri = str_replace(')','%29',$uri);
  $uri = str_replace('?','%3F',$uri);
  $uri = str_replace('-','_',$uri);
  $uri = str_replace('à','aa',$uri);
  $uri = str_replace('è','ee',$uri);
  $uri = str_replace('ì','ii',$uri);
  $uri = str_replace('ò','oo',$uri);
  $uri = str_replace('ù','uu',$uri);
  $uri = str_replace('À','AA',$uri);
  $uri = str_replace('È','EE',$uri);
  $uri = str_replace('Ì','II',$uri);
  $uri = str_replace('Ò','OO',$uri);
  $uri = str_replace('Ù','UU',$uri);
  echo $uri . PHP_EOL;
  if ($tmp[8]=='M') echo '  a :MasculineNoun ;' . PHP_EOL;
  if ($tmp[8]=='F') echo '  a :FeminineNoun ;' . PHP_EOL;
  echo '  rdfs:label "' . $hw . '" ;' . PHP_EOL;
  echo '  :sense "' . $tmp[2] . '" ;' . PHP_EOL;
  if ($tmp[9]!='') echo '  :pl "' . $tmp[9] . '" ;' . PHP_EOL;
  echo '  rdfs:comment "SNH ID: ' . $tmp[0] . '" ;' . PHP_EOL;
  if ($tmp[3]!='') echo '  rdfs:comment "SNH other: ' . $tmp[3] . '" ;' . PHP_EOL;
  if ($tmp[5]!='') echo '  rdfs:comment "SNH info: ' . $tmp[5] . '" ;' . PHP_EOL;
  if ($tmp[6]!='') echo '  rdfs:comment "SNH audio: ' . $tmp[6] . '" ;' . PHP_EOL;
  if ($tmp[7]!='') echo '  rdfs:comment "SNH Latin: ' . $tmp[7] . '" ;' . PHP_EOL;
  echo '  rdfs:comment "SNH category: ' . trim($tmp[10]) . '" .' . PHP_EOL;
  echo PHP_EOL;

}

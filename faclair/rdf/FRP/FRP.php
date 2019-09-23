<?php

$file = file_get_contents('FRP-7.txt');
$array = explode(PHP_EOL.PHP_EOL, $file);



foreach ($array as $entry) {
  $tmp = explode(PHP_EOL, $entry);
  $uri = 'n:' . $tmp[0];
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
  echo '  rdfs:label "' . $tmp[0] . '" ;' . PHP_EOL;
  echo '  :sense ';
  for ($i=1; $i<count($tmp);$i++) {
    echo '"' . $tmp[$i] . '" ';
    if ($i < count($tmp)-1) echo ', ';
  }
  echo '.' . PHP_EOL;
  echo PHP_EOL;
}

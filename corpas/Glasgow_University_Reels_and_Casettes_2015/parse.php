<?php

$file = file_get_contents('HDSG-A.csv');
$array = explode(PHP_EOL, $file);

foreach ($array as $entry) {
  $tmp = explode(',', $entry);
  echo 'a:' . $tmp[2] . PHP_EOL;
  echo '  dc:title "' . $tmp[3] . '" ;' . PHP_EOL;
  echo '  dc:creator "' . $tmp[1] . '" ;' . PHP_EOL;
  echo '  dc:date "' . $tmp[6] . '" ;' . PHP_EOL;
  echo '  dc:spatial "' . $tmp[4] . '" ;' . PHP_EOL;
  echo '  dc:contributor "' . $tmp[5] . '" ;' . PHP_EOL;
  echo '  dc:extent "' . $tmp[7] . '" ;' . PHP_EOL;
  echo '  dc:description "' . $tmp[8] . '" ;' . PHP_EOL;
  echo '  dc:note "Original tape name: ' . $tmp[9] . '" ;' . PHP_EOL;
  echo '  dc:note "Physical location: ' . $tmp[10] . '" ;' . PHP_EOL;
  echo '  dc:note "Digital location: ' . $tmp[11] . '" ;' . PHP_EOL;
  echo '  dc:note "Transcribed: ' . $tmp[12] . '" ;' . PHP_EOL;
  echo '  dc:note "Proofed: ' . $tmp[13] . '" ;' . PHP_EOL;
  echo '  dc:note "VTT uploaded: ' . $tmp[14] . '" ;' . PHP_EOL;
  echo '  dc:note "Detailed contents uploaded: ' . $tmp[15] . '" ;' . PHP_EOL;
  echo '  dc:note "DASG website: ' . $tmp[16] . '" ;' . PHP_EOL;
  echo '  dc:note "DASG team check: ' . $tmp[17] . '" ;' . PHP_EOL;
  echo '  dc:note "Permission granted: ' . $tmp[18] . '" ;' . PHP_EOL;
  echo '  dc:note "Audio files: ' . $tmp[19] . '" ;' . PHP_EOL;
  echo '  dc:note "Images: ' . $tmp[20] . '" ;' . PHP_EOL;
  echo '  dc:note "Brand: ' . $tmp[21] . '" ;' . PHP_EOL;
  echo '  dc:note "Size: ' . $tmp[22] . '" ;' . PHP_EOL;
  echo '  dc:note "Speed: ' . $tmp[23] . '" ;' . PHP_EOL;
  echo '  dc:note "Indexed: ' . $tmp[24] . '" ;' . PHP_EOL;
  echo '  dc:note "Notes: ' . $tmp[25] . '" ;' . PHP_EOL;
  echo '  dc:note "Capture station: ' . $tmp[26] . '" ;' . PHP_EOL;
  echo '  dc:note "Deck: ' . $tmp[27] . '" ;' . PHP_EOL;
  echo '  dc:note "Digitiser: ' . $tmp[28] . '" ;' . PHP_EOL;
  echo '  dc:note "Sadie project no: ' . trim($tmp[29]) . '" ;' . PHP_EOL;
  echo '  dc:note "Phase: ' . $tmp[0] . '" .' . PHP_EOL;
  /*
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
  */
  echo PHP_EOL;

}

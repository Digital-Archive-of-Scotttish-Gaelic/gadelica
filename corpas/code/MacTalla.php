<?php

$file = file_get_contents('MacTalla.csv');
$array = explode(PHP_EOL, $file);

foreach ($array as $entry) {
  $tmp = explode(',', $entry);
  echo 'c:Mac-Talla_' . str_replace('. ','_',$tmp[24]) . '_';
  $title = $tmp[1];
  $title = urise($title);
  echo $title . PHP_EOL;
  $title2 = $tmp[1];
  $title2 = str_replace('|',',',$title2);
  $title2 = str_replace('"','',$title2);
  echo '  dc:title "' . $title2 . '" ;' . PHP_EOL;
  echo '  rdfs:label "' . $title2 . ' (Mac-Talla ' . $tmp[24] . ')" ;' . PHP_EOL;
  echo '  dc:isVersionOf t:' . $title . '__';
  $firstLine = $tmp[5];
  $firstLine = urise($firstLine);
  echo $firstLine . ' ;' . PHP_EOL;
  $air = $tmp[3];
  if ($air!='') {
    $air = str_replace('|',',',$air);
    $air = str_replace('"','',$air);
    echo '  hs:air "' . $air . '" ;' . PHP_EOL;
  }
  $firstLineChorus = $tmp[4];
  if ($firstLineChorus!='') {
    $firstLineChorus = str_replace('|',',',$firstLineChorus);
    $firstLineChorus = str_replace('"','',$firstLineChorus);
    echo '  hs:firstLineChorus "' . $firstLineChorus . '" ;' . PHP_EOL;
  }
  $firstLineVerse = $tmp[5];
  if ($firstLineVerse!='') {
    $firstLineVerse = str_replace('|',',',$firstLineVerse);
    $firstLineVerse = str_replace('"','',$firstLineVerse);
    echo '  hs:firstLineVerse "' . $firstLineVerse . '" ;' . PHP_EOL;
  }
  echo '  hs:page "' . $tmp[27] . '" ;' . PHP_EOL;
  echo '  hs:smo "' . $tmp[28] . '" ;' . PHP_EOL;
  echo '  rdfs:comment "' . $tmp[29] . '" ;' . PHP_EOL;
  echo '  rdfs:comment "' . $tmp[30] . '" ;' . PHP_EOL;
  echo '  rdfs:comment "' . $tmp[31] . '" ;' . PHP_EOL;
  echo '  rdfs:comment "' . $tmp[32] . '" ;' . PHP_EOL;
  echo PHP_EOL;

  echo 't:' . $title . '__' . $firstLine . PHP_EOL;
  echo '  a :Song ;' . PHP_EOL;
  echo '  dc:title "' . $title2 . '" ;' . PHP_EOL;
  echo '  rdfs:label "' . $title2 . '" ;' . PHP_EOL;
  $title3 = $tmp[2];
  if ($title3!='') {
    $title3 = str_replace('|',',',$title3);
    $title3 = str_replace('"','',$title3);
    echo '  hs:alternativeTitle "' . $title3 . '" ;' . PHP_EOL;
  }
  $classifications = explode(' | ', $tmp[6]);
  foreach ($classifications as $nextType){
    echo '  dc:type "' . $nextType . '" ;' . PHP_EOL;
  }
  $subjects = explode(' | ', $tmp[7]);
  foreach ($subjects as $nextSubject){
    echo '  dc:subject "' . $nextSubject . '" ;' . PHP_EOL;
  }
  $structure = $tmp[8];
  echo '  hs:structure "' . $structure . '" ;' . PHP_EOL;
  $creator = $tmp[11] . '_' . $tmp[12] . '_' . $tmp[14];
  $creator = urise($creator);
  if ($creator!='') {
    echo '  dc:creator p:' . $creator . ' ;' . PHP_EOL;
  }
  if ($tmp[17]!='') {
    echo '  dc:date "' . $tmp[17] . '" ;' . PHP_EOL;
  }
  echo PHP_EOL;

  echo 'p:' . $creator . PHP_EOL;
  echo '  rdfs:label "' . $tmp[11] . ' ' . $tmp[12] . '" ;' . PHP_EOL;
  echo '  :dob "' . $tmp[14] . '" ;' . PHP_EOL;
  echo '  :dod "' . $tmp[14] . '" ;' . PHP_EOL;
  echo '  hs:origin "' . $tmp[9] . '" ;' . PHP_EOL;
  echo '  hs:gender "' . $tmp[10] . '" ;' . PHP_EOL;
  echo '  hs:nickname "' . $tmp[13] . '" ;' . PHP_EOL;
  echo '  hs:community "' . $tmp[15] . '" ;' . PHP_EOL;
  echo '  hs:county "' . $tmp[16] . '" .' . PHP_EOL;
  echo PHP_EOL;
}



function urise($title) {
  $title = str_replace(' ','_',$title);
  $title = str_replace('’','',$title);
  $title = str_replace('\'','',$title);
  $title = str_replace('(','',$title);
  $title = str_replace('[','',$title);
  $title = str_replace(']','',$title);
  $title = str_replace('|','',$title);
  $title = str_replace('"','',$title);
  $title = str_replace(')','',$title);
  $title = str_replace('?','',$title);
  $title = str_replace('!','',$title);
  $title = str_replace('-','_',$title);
  $title = str_replace('à','aa',$title);
  $title = str_replace('è','ee',$title);
  $title = str_replace('é','ee',$title);
  $title = str_replace('ì','ii',$title);
  $title = str_replace('ò','oo',$title);
  $title = str_replace('ó','oo',$title);
  $title = str_replace('ù','uu',$title);
  $title = str_replace('À','AA',$title);
  $title = str_replace('È','EE',$title);
  $title = str_replace('Ì','II',$title);
  $title = str_replace('Ò','OO',$title);
  $title = str_replace('Ù','UU',$title);
  return $title;
}

?>

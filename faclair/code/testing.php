<?php

$file = file_get_contents('../queryResults.json');
$array = json_decode($file, true);
//var_dump($array['results']);


foreach (($array['results'])['bindings'] as $nextResult) {
  echo $nextResult['s']['value'] . PHP_EOL ; // convert accents
  echo '  rdfs:label "' . $nextResult['gd']['value'] . '" ;' . PHP_EOL;
  echo '  :sense "' . $nextResult['en']['value'] . '" .' . PHP_EOL;
  echo PHP_EOL;
}


?>

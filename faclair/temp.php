<?php

$handle = fopen("CoS.csv", "r");

//print_r(fgetcsv($handle));

while(!feof($handle)) {
  $line = fgetcsv($handle);
  if ($line[3]) {
    echo '"1","' . $line[0] . '","' . $line[2] . '","a","';
    echo $line[3] . '"';
    echo PHP_EOL;
  }
}

fclose($handle);


 ?>

<?php

$files = scandir('html');
foreach ($files as $nextFile) {
  if (strpos($nextFile, '.html')) {
    $file = file_get_contents('html/' . $nextFile);
    $i = strpos($file,'ViewEntry.aspx?ID=', 0);
    while($i) {
      echo substr($file,$i,50) . PHP_EOL;
      $i = strpos($file,'ViewEntry.aspx?ID=', $i+1);
    }
    $i = strpos($file,'ViewDictionaryEntry.aspx?ID=', 0);
    while($i) {
      echo substr($file,$i,60) . PHP_EOL;
      $i = strpos($file,'ViewDictionaryEntry.aspx?ID=', $i+1);
    }
  }
}

?>

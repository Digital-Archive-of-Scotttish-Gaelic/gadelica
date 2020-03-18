<?php

$files = scandir('../xml');
foreach ($files as $nextFile) {
  if (substr($nextFile,-4)=='.xml') {
    echo $nextFile . ' is an XML file' . PHP_EOL;
  }
  else if (is_dir($nextFile)) {
    echo $nextFile . ' is a directory' . PHP_EOL;
  }
  else {
    echo $nextFile . ' is a bum' . PHP_EOL;
  }
}

?>

<?php

$xmls = [];
$path = '../xml/';
$files = scandir($path);
foreach ($files as $nextFile) {
  if (substr($nextFile,-4)=='.xml') {
    echo $path . $nextFile . PHP_EOL;
  }
  else if ($nextFile!='.' && $nextFile!='..') {
    $path2 = $path . $nextFile . '/';
    if (is_dir($path2)) {
      $files2 = scandir($path2);
      foreach ($files2 as $nextFile2) {
        if (substr($nextFile2,-4)=='.xml') {
          echo $path2 . $nextFile2 . PHP_EOL;
        }
        else if ($nextFile2!='.' && $nextFile2!='..') {
          $path3 = $path2 . $nextFile2 . '/';
          if (is_dir($path3)) {
            $files3 = scandir($path3);
            foreach ($files3 as $nextFile3) {
              if (substr($nextFile3,-4)=='.xml') {
                echo $path3 . $nextFile3 . PHP_EOL;
              }
            }
          }
        }
      }
    }
  }
}



?>

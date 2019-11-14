<?php

$file = file_get_contents('../ids-1.txt');
$ids = explode(PHP_EOL, $file);

foreach ($ids as $nextId) {
  if (strpos($nextId,'Dictionary')) {
    $url = 'https://www.faclair.com/' . $nextId;
    //echo 'Getting ' . $url . ' . . . ' . PHP_EOL;
    $id = substr($nextId,28);
    //echo $id . PHP_EOL;
    $html = file_get_contents($url);
    $fileName = $id . '.html';
    echo $fileName . PHP_EOL;
    file_put_contents($fileName,$html);
  }

}

?>

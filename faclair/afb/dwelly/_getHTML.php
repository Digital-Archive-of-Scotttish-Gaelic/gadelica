<?php

$file = file_get_contents('../afb-next.txt');
$ids = explode(PHP_EOL, $file);

foreach ($ids as $nextId) {
    $url = 'https://www.faclair.com/' . $nextId;
    //echo 'Getting ' . $url . ' . . . ' . PHP_EOL;
    $id = substr($nextId,18);
    //echo $id . PHP_EOL;
    $html = file_get_contents($url);
    $fileName = '../html/' . $id . '.html';
    echo $fileName . PHP_EOL;
    file_put_contents($fileName,$html);

}

?>

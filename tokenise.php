<?php

$path = './xml2';
$it = new RecursiveDirectoryIterator($path);
foreach (new RecursiveIteratorIterator($it) as $nextFile) {
  if ($nextFile->getExtension()=='xml') {
    //echo $nextFile->getFilename() . PHP_EOL;
    //$xml = simplexml_load_file($nextFile);
    
    $xmlDoc = new DOMDocument();
    $xmlDoc->load($nextFile);
    $i = $xmlDoc->documentElement;
    //echo $i->nodeName . PHP_EOL;
    foreach($i->childNodes as $nextBlock) {
      $b =  $nextBlock->nodeName;
      if ($b=='h' || $b=='p') {
        //echo $b . ' ';
        foreach($nextBlock->childNodes as $nextElement) {
          $c =  $nextElement->nodeName;
          if ($c=='pc' && $nextElement->nodeValue=='’') {
            echo $nextElement->nextElementSibling->nodeValue . ' ';
          }
        }
        
        
      }
    }
    
    //print $xmlDoc->saveXML();
    
    //$xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    //foreach ($xml->xpath("//dasg:w") as $nextWord) {
    //}
  }
}


?>
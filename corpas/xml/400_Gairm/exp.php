<?php

$file = "1.xml";
$xml = new SimpleXMLElement($file,0,true);
//echo get_class($xml) . PHP_EOL;
//echo $xml['ref'] . PHP_EOL;

$dom = dom_import_simplexml($xml);
//echo get_class($dom) . PHP_EOL;

$dom->ownerDocument->xinclude();

/*
$x = $xml->text;
foreach ($x as $nextx) {
  echo $nextx['ref'] . PHP_EOL;
}
*/

$as = $xml->attributes();
foreach ($as as $nexta => $nextv) {
  echo $nexta . ': ' . $nextv . PHP_EOL;
}



//echo gettype($x);


//echo $x->asXML();

//echo $xml->getName();

?>
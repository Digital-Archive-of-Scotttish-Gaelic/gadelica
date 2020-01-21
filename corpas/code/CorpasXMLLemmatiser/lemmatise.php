<?php

require_once 'includes.php';

foreach (new DirectoryIterator(INPUT_FILEPATH) as $fileinfo) {
  if ($fileinfo->isDot()) continue;
  $filename = $fileinfo->getFilename();
  $handler = new FileHandler($filename);
  $xml = $handler->getXml();
  $lemmatiser = new Lemmatiser($xml);
  $procXml = $lemmatiser->getProcessedXml();
  $handler->saveXml($procXml);
}

echo "\n\nProcess complete\n\n";




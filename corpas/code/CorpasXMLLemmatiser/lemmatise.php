<?php

require_once 'includes.php';

foreach (new DirectoryIterator(INPUT_FILEPATH) as $fileinfo) {
  if ($fileinfo->isDot()) continue;
  //start the clock running to track time
  $startTime = new DateTime();
  $filename = $fileinfo->getFilename();
  $handler = new FileHandler($filename);
  $xml = $handler->getXml();
  $lemmatiser = new Lemmatiser($xml);
  $procXml = $lemmatiser->getProcessedXml();
  $handler->saveXml($procXml);
  $elapsedTime = $startTime->diff(new DateTime());
  echo " -- " . $elapsedTime->format('%H:%I:%S');
}

echo "\n\nProcess complete\n\n";




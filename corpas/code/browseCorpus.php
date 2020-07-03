<?php

require_once "includes/htmlHeader.php";

echo <<<HTML
      <table class="table">
        <tbody>
HTML;
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?uri ?rank ?title ?xml ?part ?writer ?surname ?forenames ?nickname ?date
WHERE
{
  ?uri dc:identifier ?rank .
  ?uri dc:title ?title .
  ?uri dc:date ?date .
  OPTIONAL {
    ?uri dc:creator ?writer .
    OPTIONAL {
      ?writer :surnameGD ?surname .
      ?writer :forenamesGD ?forenames .
      OPTIONAL {
        ?writer :nickname ?nickname .
      }
    }
  }
  OPTIONAL { ?uri :xml ?xml . }
  OPTIONAL { ?part dc:isPartOf ?uri . }
  FILTER NOT EXISTS { ?uri dc:isPartOf ?superuri . }
}
ORDER BY ?rank
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Corpus?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/corpas/code' || getcwd()=='/Users/stephenbarrett/Sites/gadelica/corpas/code') {
  $url = 'http://localhost:3030/Corpus?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
$texts = [];
foreach ($results as $nextResult) {
  $texts[] = $nextResult->uri->value;
}
$texts = array_unique($texts);
foreach ($texts as $nextText) {
  $rank = '';
  $title = '';
  $done = false;
  foreach ($results as $nextResult) {
    if ($nextResult->uri->value==$nextText) {
      $rank = $nextResult->rank->value;
      $title = $nextResult->title->value;
      $done = $nextResult->xml->value!='' || $nextResult->part->value!='' ;
      break;
    }
  }
  echo '<tr><td>#';
  echo $rank . '</td><td style="width: 50%;">';
  if ($done) { echo '<strong>'; }
  echo '<a href="viewText.php?uri=' . $nextText . '">' . $title . '</a>';
  if ($done) { echo '</strong>'; }
  echo '</td><td>';
  $writers = [];
  foreach ($results as $nextResult) {
    if ($nextResult->uri->value==$nextText) {
      $nextWriter = $nextResult->writer->value;
      if ($nextWriter!='') {
        $writers[] = $nextWriter;
      }
    }
  }
  $writers = array_unique($writers);
  foreach ($writers as $nextWriter) {
    if (substr($nextWriter,0,8)=='https://') {
      foreach ($results as $nextResult) {
        if ($nextResult->writer->value==$nextWriter) {
          echo '<a href="viewWriter.php?uri=' . $nextWriter . '">';
          echo $nextResult->forenames->value;
          echo ' ';
          echo $nextResult->surname->value;
          echo '</a>';
          if ($nextResult->nickname->value!='') {
            echo ' (';
            echo $nextResult->nickname->value;
            echo ')';
          }
          break;
        }
      }
    }
    else { echo $nextWriter; }
    if ($nextWriter !== end($writers)) { echo ', '; }
  }
  echo '</td><td>';
  foreach ($results as $nextResult) {
    if ($nextResult->uri->value==$nextText) {
      echo $nextResult->date->value;
      break;
    }
  }
  echo '</td></tr>';
}
echo <<<HTML
        </tbody>
      </table>
HTML;

require_once "includes/htmlFooter.php";

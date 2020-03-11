<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Corpas na Gàidhlig</title>
  </head>
  <body>
    <div class="container-fluid">
      <h1>Corpas na Gàidhlig</h1>
      <table class="table">
        <tbody>
<?php
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?uri ?rank ?title ?xml ?part ?creator ?surname ?forenames
WHERE
{
  ?uri dc:identifier ?rank .
  ?uri dc:title ?title .
  OPTIONAL {
    ?uri dc:creator ?creator .
    OPTIONAL {
      ?creator :surnameGD ?surname .
      ?creator :forenamesGD ?forenames .
    }
  }
  OPTIONAL { ?uri :xml ?xml . }
  OPTIONAL { ?part dc:isPartOf ?uri . }
  FILTER NOT EXISTS { ?uri dc:isPartOf ?superuri . }
}
ORDER BY ?rank
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Corpus?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/corpas/code') {
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
  $creator = '';
  $surname = '';
  foreach ($results as $nextResult) {
    if ($nextResult->uri->value==$nextText) {
      $rank = $nextResult->rank->value;
      $title = $nextResult->title->value;
      $done = $nextResult->xml->value!='' || $nextResult->part->value!='' ;
      $creator = $nextResult->creator->value;
      $name = $nextResult->forenames->value . ' ' . $nextResult->surname->value;
      break;
    }
  }
  echo '<tr><td>#';
  echo $rank . '</td><td style="width: 50%;">';
  if ($done) { echo '<strong>'; }
  echo '<a href="viewText.php?uri=' . $nextText . '">' . $title . '</a>';
  if ($done) { echo '</strong>'; }
  echo '</td><td>';
  if (substr($creator,0,8)=='https://') {
    echo '<a href="viewWriter.php?uri=' . $creator . '">' . $name . '</a>';
  }
  else { echo $creator; }
  echo '</td></tr>';
}
?>
        </tbody>
      </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>

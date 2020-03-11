<!doctype html>
<?php
function getSuperWriters($uri) {
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?superuri ?writer ?surname ?forenames
WHERE
{
  <{$uri}> dc:isPartOf ?superuri .
  OPTIONAL {
    ?superuri dc:creator ?writer .
    OPTIONAL {
      ?writer :surnameGD ?surname .
      ?writer :forenamesGD ?forenames .
    }
  }
}
SPQR;
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Corpus?output=json&query=' . urlencode($query);
  if (getcwd()=='/Users/mark/Sites/gadelica/corpas/code') {
    $url = 'http://localhost:3030/Corpus?output=json&query=' . urlencode($query);
  }
  $json = file_get_contents($url);
  $results = json_decode($json,false)->results->bindings;
  if (count($results)==0) {
    return [];
  }
  $writers = [];
  foreach ($results as $nextResult) {
    $writers[$nextResult->writer->value] = $nextResult->forenames->value . ' ' . $nextResult->surname->value;
  }
  if (count($writers)==0) {
    return getSuperWriters($results[0]->superuri->value);
  }
  return $writers;
}
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Corpas na Gàidhlig</title>
  </head>
  <body>
    <div class="container-fluid" style="max-width: 800px; float: left;">
<?php
$uri = $_GET['uri'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?title ?id ?suburi ?suburiTitle ?suburiRank ?xml ?medium ?genre ?writer ?surname ?forenames
WHERE
{
  <{$uri}> dc:title ?title .
  <{$uri}> dc:identifier ?id .
  OPTIONAL { <{$uri}> :xml ?xml . }
  OPTIONAL {
    ?suburi dc:isPartOf <{$uri}> .
    ?suburi dc:title ?suburiTitle .
    ?suburi dc:identifier ?suburiRank .
  }
  OPTIONAL { <{$uri}> :medium ?medium . }
  OPTIONAL { <{$uri}> :genre ?genre . }
  OPTIONAL {
    <{$uri}> dc:creator ?writer .
    OPTIONAL {
      ?writer :surnameGD ?surname .
      ?writer :forenamesGD ?forenames .
    }
  }
}
ORDER BY ?suburiRank
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Corpus?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/corpas/code') {
  $url = 'http://localhost:3030/Corpus?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
$id = $results[0]->id->value;
echo '<h1>#' . $id . ': ' . $results[0]->title->value . '</h1>';

// META:
echo '<table class="table"><tbody>';
$writers = [];
foreach ($results as $nextResult) {
  $nextWriter = $nextResult->writer->value;
  if ($nextWriter!='') {
    $writers[$nextWriter] = $nextResult->forenames->value . ' ' . $nextResult->surname->value;
  }
}
if (count($writers)==0) {
  $writers = getSuperWriters($uri);
}
if (count($writers)>0) {
  echo '<tr><td>';
  echo 'writer</td><td>';
  foreach ($writers as $nextWriter=>$nextName) {
    if (substr($nextWriter,0,8)=='https://') {
      echo '<a href="viewWriter.php?uri=' . $nextWriter . '">';
      echo $nextName;
      echo '</a>';
    }
    else { echo $nextWriter; }
    if ($nextWriter !== end(array_keys($writers))) { echo ', '; }
  }
  echo '</td></tr>';
}

$media = [];
foreach ($results as $nextResult) {
  $nextMedium = $nextResult->medium->value;
  if ($nextMedium!='') {
    $media[] = $nextMedium;
  }
}
$media = array_unique($media);
if (count($media)>0) {
  echo '<tr><td>';
  echo 'medium</td><td>';
  echo implode($media,', ');
  echo '</td></tr>';
}
$genres = [];
foreach ($results as $nextResult) {
  $nextGenre = $nextResult->genre->value;
  if ($nextGenre!='') {
    $genres[] = $nextGenre;
  }
}
$genres = array_unique($genres);
if (count($genres)>0) {
  echo '<tr><td>';
  echo 'genre</td><td>';
  echo implode($genres,', ');
  echo '</td></tr>';
}
echo '</tbody></table>';
echo '<p>&nbsp;</p>';

// PARTS:
$subURIs = [];
foreach ($results as $nextResult) {
  $nextSubURI = $nextResult->suburi->value;
  if ($nextSubURI!='') {
    $subURIs[] = $nextSubURI;
  }
}
$subURIs = array_unique($subURIs);
if (count($subURIs)>0) {
  echo '<div class="list-group list-group-flush">';
  foreach ($subURIs as $nextSubURI) {
    echo '<div class="list-group-item list-group-item-action">';
    $subURITitle = 'Poo';
    $subURIRank = 0;
    foreach ($results as $nextResult) {
      $nextSubURI2 = $nextResult->suburi->value;
      if ($nextSubURI2==$nextSubURI) {
        $subURITitle = $nextResult->suburiTitle->value;
        $subURIRank = $nextResult->suburiRank->value;
        break;
      }
    }
    echo '#' . $subURIRank . ': <a href="viewText.php?uri=' . $nextSubURI .'">' . $subURITitle;
    echo '</a></div>';
  }
  echo '</div>';
}

// XML:
$xml = $results[0]->xml->value;
if ($xml!='') {
  $text = new SimpleXMLElement("../xml/" . $xml, 0, true);
  $xsl = new DOMDocument;
  $xsl->load('corpus.xsl');
  $proc = new XSLTProcessor;
  $proc->importStyleSheet($xsl);
  echo $proc->transformToXML($text);
}
?>


    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
    </script>

  </body>
</html>

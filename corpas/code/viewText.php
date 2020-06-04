<?php
function getSuperMedia($uri) {
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?superuri ?medium
WHERE
{
  <{$uri}> dc:isPartOf ?superuri .
  OPTIONAL {
    ?superuri :medium ?medium .
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
  $media = [];
  foreach ($results as $nextResult) {
    $media[] = $nextResult->medium->value;
  }
  if (count($media)==0) {
    return getSuperMedia($results[0]->superuri->value);
  }
  return array_unique($media);
}

function getSuperGenres($uri) {
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?superuri ?genre
WHERE
{
  <{$uri}> dc:isPartOf ?superuri .
  OPTIONAL {
    ?superuri :genre ?genre .
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
  $genres = [];
  foreach ($results as $nextResult) {
    $genres[] = $nextResult->genre->value;
  }
  if (count($genres)==0) {
    return getSuperGenres($results[0]->superuri->value);
  }
  return array_unique($genres);
}

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
require_once "includes/htmlHeader.php";
//echo '<body data-hi="' . $_GET['id'] . '">';
//<div class="container-fluid" style="max-width: 800px; float: left;">
$uri = $_GET['uri'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?title ?id ?suburi ?suburiTitle ?suburiRank ?xml ?medium ?genre ?writer ?surname ?forenames ?nick ?date ?publisher ?rating ?superuri ?supertitle
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
      OPTIONAL {
        ?writer :nickname ?nick .
      }
    }
  }
  OPTIONAL { <{$uri}> dc:date ?date . }
  OPTIONAL { <{$uri}> dc:publisher ?publisher . }
  OPTIONAL { <{$uri}> :rating ?rating . }
  OPTIONAL {
    <{$uri}> dc:isPartOf ?superuri .
    ?superuri dc:title ?supertitle .
  }
}
ORDER BY ?suburiRank
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Corpus?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/corpas/code') {
  $url = 'http://localhost:3030/Corpus?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
//echo $json;
$results = json_decode($json,false)->results->bindings;
$id = $results[0]->id->value;
echo '<h3>' . $results[0]->title->value . '</h3>';
// META:
echo '<table class="table" id="meta" data-hi="' . $_GET['id'] . '"><tbody>';
$writers = [];
foreach ($results as $nextResult) {
  $nextWriter = $nextResult->writer->value;
  if ($nextWriter!='') {
    $name = $nextResult->forenames->value . ' ' . $nextResult->surname->value;
    if ($nextResult->nick->value!='') {
      $name .= ' (' . $nextResult->nick->value . ')';
    }
    $writers[$nextWriter] = $name;
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
if (count($media)==0) {
  $media = getSuperMedia($uri);
}
if (count($media)>0) {
  echo '<tr><td>medium</td><td>';
  foreach ($media as $nextMedium) {
    echo '<a class="badge badge-primary" href="#">';
    echo $nextMedium;
    echo '</a> ';
  }
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
if (count($genres)==0) {
  $genres = getSuperGenres($uri);
}
if (count($genres)>0) {
  echo '<tr><td>genre</td><td>';
  foreach ($genres as $nextGenre) {
    echo '<a class="badge badge-primary" href="#">';
    echo $nextGenre;
    echo '</a> ';
  }
  echo '</td></tr>';
}
$date = $results[0]->date->value;
if ($date!='') {
  echo '<tr><td>publication year</td><td>';
  echo $results[0]->date->value;
  echo '</td></tr>';
}
$publisher = $results[0]->publisher->value;
if ($publisher!='') {
  echo '<tr><td>publisher</td><td>';
  echo $results[0]->publisher->value;
  echo '</td></tr>';
}
$rating = $results[0]->rating->value;
if ($rating!='') {
  echo '<tr><td>rating</td><td>';
  echo $results[0]->rating->value;
  echo '</td></tr>';
}
$superuri = $results[0]->superuri->value;
if ($superuri!='') {
  echo '<tr><td>part of</td><td>';
  echo '<a href="viewText.php?uri=' . $superuri . '">';
  echo $results[0]->supertitle->value;;
  echo '</a></td></tr>';
}

echo '<tr><td>URI</td><td>' . $uri . '</td></tr>';
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
    <script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip();
  hi = $('#meta').attr('data-hi');
  $('#'+hi).css('background-color', 'yellow');
  $('body').animate({scrollTop: $('#'+hi).offset().top - 180},500);
});
    </script>
  </body>
</html>

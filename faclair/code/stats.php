<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Stòras-Brì</title>
  </head>
  <body>
    <div class="container-fluid">
<?php
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id
WHERE
{
  ?id rdfs:label ?gd .
}
SPQR;
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
$results = json_decode(file_get_contents($url),false)->results->bindings;
echo '<p>' . count($results) . ' matrix lexemes (with headwords)</p>';
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id
WHERE
{
  ?id rdfs:label ?gd1 .
  ?id rdfs:label ?gd2 .
  FILTER (?gd1 != ?gd2)
}
SPQR;
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
$results = json_decode(file_get_contents($url),false)->results->bindings;
echo '<p>' . count($results) . ' with more than one headword: <ul>';
foreach ($results as $nextResult) {
  echo '<li>' . $nextResult->id->value . '</li>';
}
echo '</ul></p>';
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id
WHERE
{
  ?id rdfs:label ?gd .
  FILTER NOT EXISTS {
    GRAPH ?g {
      ?id :sense ?en .
    }
  }
}
SPQR;
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
$results = json_decode(file_get_contents($url),false)->results->bindings;
echo '<p>' . count($results) . ' with no translation in any sub-lexicon: <ul>';
foreach ($results as $nextResult) {
  echo '<li>' . $nextResult->id->value . '</li>';
}
echo '</ul></p>';
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?g
WHERE
{
  GRAPH ?g { }
}
SPQR;
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
$results = json_decode(file_get_contents($url),false)->results->bindings;
foreach ($results as $nextResult) {
  echo '<p>' . $nextResult->g->value . '</p><ul>';
  $query2 = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id
WHERE
{
  GRAPH <{$nextResult->g->value}> {
   ?id rdfs:label ?gd .
  }
}
SPQR;
  //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  $url2 = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query2);
  $results2 = json_decode(file_get_contents($url2),false)->results->bindings;
  echo '<li>' . count($results2) . ' lexemes</li>';
  echo '</ul>';
}



/*
$hws = [];
foreach ($results as $nextResult) {
  $pair = array($nextResult->gd->value, $nextResult->id->value);
  $hws[] = implode("|", $pair);
}
$hws = array_unique($hws);
*/
?>



      <nav class="navbar navbar-dark bg-primary fixed-bottom navbar-expand-lg">
        <a class="navbar-brand" href="index.php">Stòras Brì</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
             <a class="nav-item nav-link" href="about.html" data-toggle="tooltip" title="About this site">fios</a>
             <a class="nav-item nav-link" href="gaelicIndex.php" data-toggle="tooltip" title="Index">indeacs</a>
             <a class="nav-item nav-link" href="random.php" data-toggle="tooltip" title="View random entry">iongnadh</a>
          </div>
        </div>
      </nav>
    </div>
  </body>
</html>

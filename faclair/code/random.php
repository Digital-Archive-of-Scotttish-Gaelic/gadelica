<?php
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id
WHERE
{
  GRAPH <http://faclair.ac.uk/sources/general> {
    ?id rdfs:label ?hw .
  }
}
SPQR;
//$query = urlencode($query);
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . $query;
$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
$json = file_get_contents($url);
$data = json_decode($json,false)->results->bindings;
$ids = [];
foreach ($data as $datum) {
  $ids[] = $datum->id->value;
}
$rand = array_rand($ids);
$id = $ids[$rand];
include('viewEntry.php');

?>

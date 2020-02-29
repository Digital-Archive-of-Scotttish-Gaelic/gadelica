<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Mac-Talla</title>
  </head>
  <body>
    <div class="container-fluid">
      <h1>Mac-Talla</h1>
      <div class="list-group list-group-flush">
<?php
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?title ?volume
WHERE
{
  ?volume dc:isPartOf <https://dasg.ac.uk/corpus/_81> .
  ?volume dc:title ?title .
}
ORDER BY ?title
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/MacTalla?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/corpas/Mac-Talla/code') {
  $url = 'http://localhost:3030/MacTalla?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
$volumes = json_decode($json,false)->results->bindings;
foreach ($volumes as $nextVolume) {
  echo '<a class="list-group-item list-group-item-action" href="showVolume.php?id=';
  echo $nextVolume->volume->value;
  echo '">';
  echo $nextVolume->title->value;
  echo '</a>';
}
?>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>

<!doctype html>
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
SELECT DISTINCT ?surname ?forenames ?yob ?yod ?origin ?work
WHERE
{
  <{$uri}> :surnameGD ?surname .
  <{$uri}> :forenamesGD ?forenames .
  OPTIONAL {
    <{$uri}> :yob ?yob .
  }
  OPTIONAL {
    <{$uri}> :yod ?yod .
  }
  OPTIONAL {
    <{$uri}> :where ?origin .
  }
  ?work dc:creator <{$uri}> .
}
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Corpus?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/corpas/code') {
  $url = 'http://localhost:3030/Corpus?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
//echo $json;
$results = json_decode($json,false)->results->bindings;
echo '<h1>' . $results[0]->forenames->value . ' ' . $results[0]->surname->value . '</h1>';
echo '<table class="table"><tbody>';
if ($results[0]->yob->value!='') {
  echo '<tr><td>lifespan</td><td>';
  echo $results[0]->yob->value . ' – ' . $results[0]->yod->value;
  echo '</td></tr>';
}
if ($results[0]->origin->value!='') {
  echo '<tr><td>origin</td><td><a class="badge badge-primary" href="#">';
  echo $results[0]->origin->value;
  echo '</td></tr>';
}
echo '</tbody></table>';
$works = [];
foreach ($results as $nextResult) {
  if ($nextResult->work->value!='') {
    $works[] = $nextResult->work->value;
  }
}
$works = array_unique($works);
foreach ($works as $nextWork) {
  echo '<a href="viewText.php?uri=' . $nextWork . '">' . $nextWork . '</a><br/>';
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

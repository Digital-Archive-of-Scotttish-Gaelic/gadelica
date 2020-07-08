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
      <p><a href="index.php">&lt; Back to corpus index</a></p>
      <h1>Corpas na Gàidhlig writers</h1>
      <table class="table">
        <tbody>
<?php
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?writer ?surname ?forenames ?nickname ?yob ?yod ?origin
WHERE
{
  ?uri dc:creator ?writer .
  OPTIONAL { ?writer :surnameGD ?surname . }
  OPTIONAL { ?writer :forenamesGD ?forenames . }
  OPTIONAL { ?writer :nickname ?nickname . }
  OPTIONAL { ?writer :yob ?yob . }
  OPTIONAL { ?writer :yod ?yod . }
  OPTIONAL { ?writer :where ?origin . }
  FILTER isURI(?writer) .
}
ORDER BY ?surname
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Corpus?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/corpas/code') {
  $url = 'http://localhost:3030/Corpus?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
foreach ($results as $nextResult) {
  echo '<tr><td>';
  echo '<a href="viewWriter.php?uri=' . $nextResult->writer->value . '">';
  echo $nextResult->forenames->value;
  echo ' ';
  echo $nextResult->surname->value;
  echo '</a>';
  $nick = $nextResult->nickname->value;
  if ($nick!='') {
    echo ' (' . $nick . ')';
  }
  echo '</td><td>';
  echo $nextResult->yob->value;
  echo ' – ';
  echo $nextResult->yod->value;
  echo '</td><td><a href="#" class="badge badge-primary">';
  echo $nextResult->origin->value;
  echo '</a></td></tr>';
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

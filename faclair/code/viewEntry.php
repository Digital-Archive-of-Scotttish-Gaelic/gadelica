<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Stòras-B</title>
  </head>
  <body style="padding-top: 20px;">
    <div class="container-fluid">
<?php
$id = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?hw ?pid ?phw ?en ?lex
WHERE
{
  <{$id}> rdfs:label ?hw .
  OPTIONAL {
    <{$id}> :part ?pid .
    ?pid rdfs:label ?phw .
  }
  GRAPH ?g {
    <{$id}> :sense ?en .
  }
  ?g rdfs:label ?lex .
}
SPQR;
$query = urlencode($query);
$url = 'http://pluto.arts.gla.ac.uk:8080/fuseki/storas-b?output=json&query=' . $query;
$json = file_get_contents($url);
$data = json_decode($json,false)->results->bindings;
echo '<div class="card"><div class="card-body">';
echo '<h1 class="card-title">' . $data[0]->hw->value . '</h1>';
$ens = [];
foreach($data as $next) {
  $ens[] = $next->en->value;
}
$ens = array_unique($ens);
foreach ($ens as $next) {
  echo '<div class="list-group list-group-flush">';
  echo '<div class="list-group-item">' . $next . ' (';
  $sources = [];
  foreach ($data as $next2) {
    if ($next2->en->value==$next) {
      $sources[] = $next2->lex->value;
    }
  }
  $sources = array_unique($sources);
  foreach ($sources as $next2) {
    echo $next2;
    if ($next2 != end($sources)) {
      echo ', ';
    }
  }
  echo ')</div>';
  echo '</div>';
}

/*
echo '<div class="list-group list-group-flush">';
foreach($data as $next) {
  echo '<a href="viewEntry.php?id=' . urlencode($next->pid->value) . '" class="list-group-item list-group-item-action">' . $next->phw->value . '</a>';
}
echo '</div>';

$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?pid ?phw
WHERE
{
  ?pid :part <{$id}> .
  ?pid rdfs:label ?phw .
}
SPQR;
$query = urlencode($query);
$url = 'http://daerg.arts.gla.ac.uk:8080/fuseki/co-aite?output=json&query=' . $query;
$json = file_get_contents($url);
$data = json_decode($json,false)->results->bindings;
echo '<div class="list-group list-group-flush">';
foreach($data as $next) {
  echo '<a href="viewEntry.php?id=' . urlencode($next->pid->value) . '" class="list-group-item list-group-item-action">' . $next->phw->value . '</a>';
}
echo '</div>';

$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?lexicon ?hw ?en
WHERE
{
  GRAPH ?lexicon {
    <{$id}> rdfs:label ?hw .
    <{$id}> :sense ?en .
  }
}
SPQR;
$query = urlencode($query);
$url = 'http://daerg.arts.gla.ac.uk:8080/fuseki/co-aite?output=json&query=' . $query;
$json = file_get_contents($url);
$data = json_decode($json,false)->results->bindings;
$entries = [];
foreach($data as $next) {
  $entries[] = $next->lexicon->value;
}
$entries = array_unique($entries);
foreach($entries as $next) {
  echo '<div class="card"><div class="card-body">';
  foreach($data as $next2) {
    if($next2->lexicon->value==$next) {
      echo '<h4 class="card-title">' . $next2->hw->value . '</h4>';
      break;
    }
  }
  echo '<div class="list-group list-group-flush">';
  foreach($data as $next2) {
    if($next2->lexicon->value==$next) {
      echo '<div class="list-group-item">' . $next2->en->value . '</div>';
    }
  }
  echo '</div>';


  echo '</div></div>';
}
*/
echo '</div></div>';


?>
    <nav class="navbar navbar-dark bg-primary fixed-bottom navbar-expand-lg">
      <a class="navbar-brand" href="index.php">Stòras-B</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
           <!-- <a class="nav-item nav-link" href="gaelic.php" data-toggle="tooltip" title="Switch to Gaelic-to-English search">Gàidhlig gu Beurla</a>-->
        </div>
      </div>
    </nav>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>

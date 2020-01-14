<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Lexicon</title>
  </head>
  <body style="padding-top: 20px;">
    <div class="container-fluid">
<?php
$id = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?hw ?en ?pid ?phw ?cid ?chw
WHERE
{
  <{$id}> rdfs:label ?hw .
  OPTIONAL { <{$id}> :sense ?en . }
  OPTIONAL {
    <{$id}> :part ?pid .
    ?pid rdfs:label ?phw .
  }
  OPTIONAL {
    ?cid :part <{$id}> .
    ?cid rdfs:label ?chw .
  }
}
SPQR;
//$query = urlencode($query);
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . $query;
$url = 'http://localhost:3030/Germanica?output=json&query=' . urlencode($query);
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
echo '<div class="card"><div class="card-body">';
echo '<h1 class="card-title">' . $results[0]->hw->value . '</h1>';
$ens = [];
foreach($results as $nextResult) {
  $ens[] = $nextResult->en->value;
}
$ens = array_unique($ens);
echo '<div class="text-muted">';
foreach ($ens as $nextEn) {
  echo $nextEn;
  if ($nextEn != end($ens)) {
    echo ', ';
  }
}
echo '</div> ';

$parts = [];
foreach($results as $nextResult) {
  $pid = $nextResult->pid->value;
  if ($pid!='') {
    //$parts[] = $pid;
    $parts[$pid] = $nextResult->phw->value;
  }
}
$parts = array_unique($parts);
if (count($parts)>0) {
  echo '<div>See: ';
  //foreach ($parts as $nextPart) {
  foreach ($parts as $nextId=>$nextHw) {
    echo '<a href="viewEntry?id=' . $nextId . '">';
    echo $nextHw . '</a>';
    if ($nextId != end($parts)) {
      echo ', ';
    }
  }
  echo '</div>';
}

$compounds = [];
foreach($results as $nextResult) {
  $cid = $nextResult->cid->value;
  if ($cid!='') {
     $compounds[$cid]= $nextResult->chw->value;
  }
}
$compounds = array_unique($compounds);
if (count($compounds)>0) {
  echo '<div>Also: ';
  foreach ($compounds as $nextId=>$nextHw) {
    echo '<a href="viewEntry?id=' . $nextId . '">';
    echo $nextHw . '</a>';
    if ($nextHw != end($compounds)) {
      echo ', ';
    }
  }
  echo '</div>';
}
echo '</div>';
echo '</div></div>';
?>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>

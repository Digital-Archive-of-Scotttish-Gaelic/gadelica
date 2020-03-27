<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Latin</title>
  </head>
  <body style="padding-top: 20px;">
    <div class="container-fluid">
      <div class="card" style="max-width: 800px;">
        <div class="card-body">
          <a href="index.php" style="float: right;">&nbsp;&lt; Back to lexeme index</a>
<?php
$id = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?hw ?en ?ipa #?stem ?stemf ?suffix ?suffixf ?cmpd ?cmpdf
WHERE
{
  <{$id}> rdfs:label ?hw .
  OPTIONAL { <{$id}> :sense ?en . }
  OPTIONAL {
    <{$id}> :stem ?stem .
    ?stem rdfs:label ?stemf .
  }
  OPTIONAL {
    <{$id}> :suffix ?suffix .
    ?suffix rdfs:label ?suffixf .
  }
  OPTIONAL {
    ?cmpd :stem <{$id}> .
    ?cmpd rdfs:label ?cmpdf .
  }
  OPTIONAL { <{$id}> :ipa ?ipa . }
}
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/la/code') {
  $url = 'http://localhost:3030/Latin?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
echo '<h1 class="card-title">';
echo $results[0]->hw->value;
echo '</h1>';
echo '<div class="list-group list-group-flush">';
$ens = []; // ENGLISH EQUIVALENTS
foreach($results as $nextResult) {
  $en = $nextResult->en->value;
  if ($en != '') {
    $ens[] = $en;
  }
}
$ens = array_unique($ens);
if (count($ens)>0) {
  echo '<div class="list-group-item text-muted">';
  echo implode(' | ', $ens);
  echo '</div>';
}
if ($results[0]->ipa->value!='') {
  echo '<div class="list-group-item text-muted">[';
  echo $results[0]->ipa->value;
  echo ']</div>';
}
/*
if ($results[0]->stem->value!='') {
  echo '<div class="list-group-item"><span class="text-muted">stem:</span> ';
  echo '<strong><a href="viewForm.php?id=' . $results[0]->stem->value . '">' . $results[0]->stemf->value . '</a></strong>';
  echo '</div>';
}
if ($results[0]->suffix->value!='') {
  echo '<div class="list-group-item"><span class="text-muted">suffix:</span> ';
  echo '<strong><a href="viewForm.php?id=' . $results[0]->suffix->value . '">' . $results[0]->suffixf->value . '</a></strong>';
  echo '</div>';
}
$cmpds = [];
foreach($results as $nextResult) {
  $cmpd = $nextResult->cmpd->value;
  if ($cmpd != '') {
    $cmpds[$nextResult->cmpd->value] = $nextResult->cmpdf->value;
  }
}
if (count($cmpds)>0) {
  echo '<div class="list-group-item"><span class="text-muted">compounds:</span> ';
  foreach ($cmpds as $nextid => $nextForm) {
    echo '<strong><a href="viewForm?id=' . $nextid . '">';
    echo $nextForm;
    echo '</a></strong>';
    if (true) { echo ', '; }
  }
  echo '</div>';
}
*/

echo '</div>';
?>
        </div> <!-- end of card-body-->
      </div> <!-- end of card -->
    </div> <!-- end of container-fluid -->
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

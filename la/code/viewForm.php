<div class="card" style="max-width: 800px;">
  <div class="card-body">
<?php
$id = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?hw ?en ?ipa ?pos
WHERE
{
  <{$id}> rdfs:label ?hw .
  OPTIONAL { <{$id}> a ?pos . }
  OPTIONAL { <{$id}> :sense ?en . }
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
$pos = $results[0]->pos->value;
if ($pos!='') {
  echo '<div class="list-group-item"><em class="text-muted">';
  if ($pos=='http://faclair.ac.uk/meta/Adverb') {
    echo 'adverb';
  }
  else if ($pos=='http://faclair.ac.uk/meta/Preposition') {
    echo 'preposition';
  }
  echo '</em></div>';
}


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
echo '</div>';
?>
  </div> <!-- end of card-body-->
</div> <!-- end of card -->

<div class="card" style="max-width: 800px;">
  <div class="card-body">
<?php
$id = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?hw ?pos ?en ?ipa ?stem ?stemf ?stemPos ?superstem ?superstemf
WHERE
{
  <{$id}> rdfs:label ?hw .
  <{$id}> a ?pos .
  OPTIONAL { <{$id}> :sense ?en . }
  OPTIONAL {
    <{$id}> :stem ?stem .
    ?stem rdfs:label ?stemf .
    ?stem a ?stemPos .
    OPTIONAL {
      ?stem :stem ?superstem .
      ?superstem rdfs:label ?superstemf .
    }
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
echo '<div class="list-group-item"><em class="text-muted">';
$conjugation = '';
foreach($results as $nextResult) {
  $pos = $nextResult->pos->value;
  if ($pos=='http://faclair.ac.uk/meta/FirstSingular') {
    $conjugation = 'first person singular';
    break;
  }
  if ($pos=='http://faclair.ac.uk/meta/SecondSingular') {
    $conjugation = 'second person singular';
    break;
  }
  if ($pos=='http://faclair.ac.uk/meta/ThirdSingular') {
    $conjugation = 'third person singular';
    break;
  }
  if ($pos=='http://faclair.ac.uk/meta/FirstPlural') {
    $conjugation = 'first person plural';
    break;
  }
  if ($pos=='http://faclair.ac.uk/meta/SecondPlural') {
    $conjugation = 'second person plural';
    break;
  }
  if ($pos=='http://faclair.ac.uk/meta/ThirdPlural') {
    $conjugation = 'third person plural';
    break;
  }
}
$tense = '';
foreach($results as $nextResult) {
  $pos = $nextResult->stemPos->value;
  if ($pos=='http://faclair.ac.uk/meta/Lexeme') {
    $tense = 'present indicative active';
    break;
  }
  if ($pos=='http://faclair.ac.uk/meta/Imperfect') {
    $tense = 'imperfect indicative active';
    break;
  }
}
echo $conjugation . ' ' . $tense . ' form of verb</em> <strong><a href="#" onclick="loadLexeme(\'';
$superstem = $results[0]->superstem->value;
if ($tense=='present indicative active') {
  echo $results[0]->stem->value . '\');">' . $results[0]->stemf->value . '</a></strong>';
}
else {
  echo $results[0]->superstem->value . '\');">' . $results[0]->superstemf->value . '</a></strong>';
}
echo '</div>';
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

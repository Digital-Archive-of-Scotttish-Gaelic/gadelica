<div class="card" style="max-width: 800px;">
  <div class="card-body">
<?php
$id = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?hw ?pos ?gender ?ipa ?stem ?stemf
WHERE
{
  <{$id}> rdfs:label ?hw .
  <{$id}> a ?pos .
  <{$id}> :ipa ?ipa .
  <{$id}> :stem ?stem .
  ?stem a ?gender .
  ?stem rdfs:label ?stemf .
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
$case = '';
$number = '';
$gender = '';
foreach($results as $nextResult) {
  $pos = $nextResult->pos->value;
  if ($pos=='http://faclair.ac.uk/meta/Nominative') {
    $case = 'nominative';
  }
  if ($pos=='http://faclair.ac.uk/meta/Accusative') {
    $case = 'accusative';
  }
  if ($pos=='http://faclair.ac.uk/meta/Genitive') {
    $case = 'genitive';
  }
  if ($pos=='http://faclair.ac.uk/meta/Dative') {
    $case = 'dative';
  }
  if ($pos=='http://faclair.ac.uk/meta/Ablative') {
    $case = 'ablative';
  }
  if ($pos=='http://faclair.ac.uk/meta/Singular') {
    $number = 'singular';
  }
  if ($pos=='http://faclair.ac.uk/meta/Plural') {
    $number = 'plural';
  }
  $pos = $nextResult->gender->value;
  if ($pos=='http://faclair.ac.uk/meta/Feminine') {
    $gender = 'feminine';
  }
  if ($pos=='http://faclair.ac.uk/meta/Masculine') {
    $gender = 'masculine';
  }
  if ($pos=='http://faclair.ac.uk/meta/Neuter') {
    $gender = 'neuter';
  }
}
echo $case . ' ' . $number . ' form of ' . $gender . ' noun</em> <strong><a href="#" onclick="loadLexeme(\'' . $results[0]->stem->value . '\');">' . $results[0]->stemf->value . '</a></strong>';
echo '</div>';
if ($results[0]->ipa->value!='') {
  echo '<div class="list-group-item text-muted">[';
  echo $results[0]->ipa->value;
  echo ']</div>';
}

echo '</div>'; // end of list group
?>
  </div> <!-- end of card-body-->
</div> <!-- end of card -->

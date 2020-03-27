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
SELECT ?hw ?en ?pos ?ns ?nsf ?as ?asf ?gs ?gsf ?ds ?dsf ?abs ?absf ?np ?npf ?ap ?apf ?gp ?gpf ?dp ?dpf ?abp ?abpf
WHERE
{
  <{$id}> rdfs:label ?hw .
  <{$id}> a ?pos .
  <{$id}> :sense ?en .
  ?ns :stem <{$id}> ; a :Nominative , :Singular ; rdfs:label ?nsf .
  ?as :stem <{$id}> ; a :Accusative , :Singular ; rdfs:label ?asf .
  ?gs :stem <{$id}> ; a :Genitive , :Singular ; rdfs:label ?gsf .
  ?ds :stem <{$id}> ; a :Dative , :Singular ; rdfs:label ?dsf .
  ?abs :stem <{$id}> ; a :Ablative , :Singular ; rdfs:label ?absf .
  ?np :stem <{$id}> ; a :Nominative , :Plural ; rdfs:label ?npf .
  ?ap :stem <{$id}> ; a :Accusative , :Plural ; rdfs:label ?apf .
  ?gp :stem <{$id}> ; a :Genitive , :Plural ; rdfs:label ?gpf .
  ?dp :stem <{$id}> ; a :Dative , :Plural ; rdfs:label ?dpf .
  ?abp :stem <{$id}> ; a :Ablative , :Plural ; rdfs:label ?abpf .
}
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/la/code') {
  $url = 'http://localhost:3030/Latin?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
echo '<h1 class="card-title">';
echo $results[0]->hw->value ;
echo '</h1>';
echo '<div class="list-group list-group-flush">';
echo '<div class="list-group-item text-muted"><em>';
foreach ($results as $nextResult) {
  if ($nextResult->pos->value=='http://faclair.ac.uk/meta/Feminine') {
    echo 'feminine';
    break;
  }
  if ($nextResult->pos->value=='http://faclair.ac.uk/meta/Masculine') {
    echo 'masculine';
    break;
  }
  if ($nextResult->pos->value=='http://faclair.ac.uk/meta/Neuter') {
    echo 'neuter';
    break;
  }
}
echo ' noun</em></div>';
$ens = []; // ENGLISH EQUIVALENTS
foreach($results as $nextResult) {
  $en = $nextResult->en->value;
  if ($en != '') {
    $ens[] = $en;
  }
}
$ens = array_unique($ens);
if (count($ens)>0) {
  echo '<div class="list-group-item">';
  echo implode(' | ', $ens);
  echo '</div>';
}
echo '</div>'; // end of list-group
?>
          <table class="table">
            <tbody>
              <tr><td></td><td><em class="text-muted">singular</em></td><td><em class="text-muted">plural</em></td></tr>
<?php
echo '<tr><td><em class="text-muted">nominative</em></td><td><strong><a href="viewNounForm.php?id=';
echo $results[0]->ns->value . '">' . $results[0]->nsf->value;
echo '</a></strong></td><td><strong><a href="viewNounForm.php?id=';
echo $results[0]->np->value . '">' . $results[0]->npf->value . '</a></strong></td></tr>';
echo '<tr><td><em class="text-muted">accusative</em></td><td><strong><a href="viewNounForm.php?id=';
echo $results[0]->as->value . '">' . $results[0]->asf->value;
echo '</a></strong></td><td><strong><a href="viewNounForm.php?id=';
echo $results[0]->ap->value . '">' . $results[0]->apf->value . '</a></strong></td></tr>';
echo '<tr><td><em class="text-muted">genitive</em></td><td><strong><a href="viewNounForm.php?id=';
echo $results[0]->gs->value . '">' . $results[0]->gsf->value;
echo '</a></strong></td><td><strong><a href="viewNounForm.php?id=';
echo $results[0]->gp->value . '">' . $results[0]->gpf->value . '</a></strong></td></tr>';
echo '<tr><td><em class="text-muted">dative</em></td><td><strong><a href="viewNounForm.php?id=';
echo $results[0]->ds->value . '">' . $results[0]->dsf->value;
echo '</a></strong></td><td><strong><a href="viewNounForm.php?id=';
echo $results[0]->dp->value . '">' . $results[0]->dpf->value . '</a></strong></td></tr>';
echo '<tr><td><em class="text-muted">ablative</em></td><td><strong><a href="viewNounForm.php?id=';
echo $results[0]->abs->value . '">' . $results[0]->absf->value;
echo '</a></strong></td><td><strong><a href="viewNounForm.php?id=';
echo $results[0]->abp->value . '">' . $results[0]->abpf->value . '</a></strong></td></tr>';
?>
            </tbody>
          </table>
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

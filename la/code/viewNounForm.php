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
echo $case . ' ' . $number . ' form of ' . $gender . ' noun</em> <strong><a href="viewNounLexeme.php?id=' . $results[0]->stem->value . '">' . $results[0]->stemf->value . '</a></strong>';
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

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
      <table class="table table-hover">
        <tbody>
<?php
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT ?id ?hw ?pos ?en
WHERE
{
  ?id a :Lexeme .
  ?id a ?pos .
  ?id rdfs:label ?hw .
  ?id :sense ?en .
}
ORDER BY ?hw
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Latin?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/la/code') {
  $url = 'http://localhost:3030/Latin?output=json&query=' . urlencode($query);
}
$results = json_decode(file_get_contents($url),false)->results->bindings;
$lexemes = [];
foreach ($results as $nextResult) {
  $lexemes[$nextResult->id->value] = $nextResult->hw->value;
}
foreach ($lexemes as $nextId=>$nextForm) {
  $pos = '';
  $ens = [];
  foreach ($results as $nextResult) {
    if ($nextResult->id->value==$nextId) {
      $temp = $nextResult->pos->value;
      if ($temp=='http://faclair.ac.uk/meta/Verb') {
        $pos = 'verb';
      }
      else if ($temp=='http://faclair.ac.uk/meta/Feminine') {
        $pos = 'feminine noun';
      }
      else if ($temp=='http://faclair.ac.uk/meta/Masculine') {
        $pos = 'masculine noun';
      }
      else if ($temp=='http://faclair.ac.uk/meta/Neuter') {
        $pos = 'neuter noun';
      }
      if ($nextResult->en->value!='') {
        $ens[] = $nextResult->en->value;
      }
    }
  }
  $ens = array_unique($ens);
  echo '<tr>';
  echo '<td><strong><a href="view';
  if ($pos=='verb') { echo 'Verb'; }
  else if (substr($pos,-4)=='noun') { echo 'Noun'; }
  echo 'Lexeme.php?id=' . $nextId . '">' . $nextForm . '</a></strong></td>';
  echo '<td><em class="text-muted">' . $pos . '</em></td>';
  echo '<td>' . implode($ens,', ') . '</td>';
  echo '</tr>';
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

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
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT ?id ?de ?en
WHERE
{
  ?id rdfs:label ?de .
  OPTIONAL { ?id :sense ?en . }
}
ORDER BY ?de
SPQR;
$url = 'http://localhost:3030/Germanica?output=json&query=' . urlencode($query);
//echo file_get_contents($url);
$results = json_decode(file_get_contents($url),false)->results->bindings;
$ids = [];
foreach ($results as $result) {
  $ids[] = $result->id->value;
}
$ids = array_unique($ids);
?>
      <table class="table table-hover">
        <tbody>
<?php
foreach ($ids as $nextid) {
  $hw = '';
  $ens = [];
  foreach ($results as $result) {
    if ($result->id->value == $nextid) {
      $hw = $result->de->value;
      $ens[] = $result->en->value;
    }
  }
  echo '<tr><td><a href="viewEntry.php?id=' . $nextid . '">' . $hw . '</a></td>';
  echo '<td><small>';
  $enstr = '';
  foreach ($ens as $nexten) {
    $enstr .= $nexten . ', ';
  }
  $enstr = trim($enstr, ', ');
  echo $enstr;
  echo '</small></td></tr>';
}
?>
        </tbody>
      </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>

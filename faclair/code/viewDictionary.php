<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
    </script>
    <title>Stòras Brì</title>
  </head>
  <body>
    <div class="container-fluid">
<?php
$id = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT ?name ?id ?hw ?pos ?en
WHERE
{
  <{$id}> rdfs:label ?name .
  GRAPH <{$id}> {
    ?id rdfs:label ?hw .
    OPTIONAL {
      ?id a ?posId .
      ?posId rdfs:label ?pos .
    }
    OPTIONAL {
      ?id :sense ?en .
    }
  }
}
SPQR;
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
//$json = file_get_contents($url);
//echo $json;
$results = json_decode(file_get_contents($url),false)->results->bindings;

echo '<h3>' . $results[0]->name->value . '</h3>';
?>
      <table class="table table-hover">
        <tbody>

<?php
$ids = [];
foreach($results as $nextResult) {
  $id = $nextResult->id->value;
  if (substr($id,0,26) != 'http://faclair.ac.uk/meta/') {
    $ids[] = $id;
  }
}
$ids = array_unique($ids);
sort($ids);
foreach($ids as $nextId) {
  echo '<tr>';
  //echo '<td><small>' . $nextId . '</small></td>';
  $hws = [];
  foreach($results as $nextResult) {
    if ($nextResult->id->value == $nextId) {
      $hws[] = $nextResult->hw->value;
    }
  }
  $hws = array_unique($hws);
  $tooltip = $nextId;
  $tooltip = str_replace('http://faclair.ac.uk/nouns/','n:',$tooltip);
  $tooltip = str_replace('http://faclair.ac.uk/adjectives/','a:',$tooltip);
  $tooltip = str_replace('http://faclair.ac.uk/verbs/','v:',$tooltip);
  $tooltip = str_replace('http://faclair.ac.uk/other/','o:',$tooltip);
  $tooltip = 'standards | '. $tooltip;
  echo '<td data-toggle="tooltip" data-placement="top" title="' . $tooltip . '">' . implode(', ',$hws) . '</td>';
  $poss = [];
  foreach($results as $nextResult) {
    if ($nextResult->id->value == $nextId) {
      $poss[] = $nextResult->pos->value;
    }
  }
  $poss = array_unique($poss);
  echo '<td>' . implode(', ',$poss) . '</td>';
  $ens = [];
  foreach($results as $nextResult) {
    if ($nextResult->id->value == $nextId) {
      $ens[] = $nextResult->en->value;
    }
  }
  $ens = array_unique($ens);
  echo '<td><small>' . implode(', ',$ens) . '</small></td>';
  echo '<td>' . 'Plurals' . '</td>';
  echo '<td>' . 'Comments' . '</td>';
  echo '</tr>';
}
?>
        </tbody>
      </table>
      <nav class="navbar navbar-dark bg-primary fixed-bottom navbar-expand-lg">
        <a class="navbar-brand" href="index.php">Stòras Brì</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
             <a class="nav-item nav-link" href="about.html" data-toggle="tooltip" title="About this site">fios</a>
             <a class="nav-item nav-link" href="gaelicIndex.php" data-toggle="tooltip" title="Index">indeacs</a>
             <a class="nav-item nav-link" href="random.php" data-toggle="tooltip" title="View random entry">iongnadh</a>
          </div>
        </div>
      </nav>
    </div>
  </body>
</html>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<?php
$search = '';
if (isset($_GET['searchTerm'])) { // check for search term in URL
  $search = $_GET['searchTerm'];
  echo '<title>Stòras Brì – ' . $search . '</title>';
}
else echo '<title>Stòras-Brì</title>';
?>
  </head>
  <body style="padding-top: 20px;">
    <div class="container-fluid">
      <form action="index.php" method="get" autocomplete="off"> <!-- Search box -->
        <div class="form-group">
          <div class="input-group">
<?php
echo '<input type="text" class="form-control active" name="searchTerm"  data-toggle="tooltip" title="Enter search term here" ';
if ($search != '') { // display search term inside search box
  echo 'value="' . $search . '"/>';
}
else { echo 'autofocus="autofocus"/>'; }
?>
            <div class="input-group-append">
              <button class="btn btn-primary" type="submit" data-toggle="tooltip" title="Click to find entries">Lorg</button>
            </div>
          </div>
        </div>
      </form>
<?php
if ($search != '') {
  echo '<table class="table table-hover"><tbody>';
  // exact English/Gaelic search (case insensitive):
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER regex(?en, "^{$search}$", "i") .
  }
  UNION
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER regex(?gd, "^{$search}$", "i") .
  }
}
SPQR;
  //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  $results = json_decode(file_get_contents($url),false)->results->bindings;
  function showResults($rs) {
    $ids = [];
    foreach ($rs as $nextResult) {
      $ids[] = $nextResult->id->value;
    }
    $ids = array_unique($ids);
    foreach ($ids as $nextid) {
      $hw = '';
      $ens = [];
      foreach ($rs as $nextResult) {
        if ($nextResult->id->value == $nextid) {
          $hw = $nextResult->gd->value;
          $ens[] = $nextResult->en->value;
        }
      }
      echo '<tr><td><a href="viewEntry.php?id=' . urlencode($nextid) . '">' . $hw . '</a></td>';
      echo '<td>';
      $enstr = '';
      foreach ($ens as $nexten) {
        $enstr .= $nexten . ', ';
      }
      $enstr = trim($enstr, ', ');
      echo $enstr;
      echo '</td></tr>';
    }
  }
  showResults($results);
  // prefix match:
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?en, "^{$search}", "i") && !(regex(?en, "{$search}$", "i"))) .
  }
  UNION
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?gd, "^{$search}", "i") && !(regex(?gd, "{$search}$", "i"))).
  }
}
ORDER BY strlen(str(?gd))
SPQR;
  //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  $results = json_decode(file_get_contents($url),false)->results->bindings;
  showResults($results);

  /*
  //suffix match:
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?en, "{$search}$", "i") && !(regex(?en, "^{$search}", "i"))) .
  }
  UNION
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?gd, "{$search}$", "i") && !(regex(?gd, "^{$search}", "i"))).
  }
}
ORDER BY strlen(str(?gd))
SPQR;
  //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  $results = json_decode(file_get_contents($url),false)->results->bindings;
  foreach ($results as $nextResult) {
    echo '<a href="viewEntry.php?id=' . urlencode($nextResult->id->value) . '" class="list-group-item list-group-item-action">' . $nextResult->gd->value . ' <small>' . $nextResult->en->value . '</small></a>';
  }
  // substring match:
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?en, "{$search}", "i") && !(regex(?en, "^{$search}", "i")) && !(regex(?en, "{$search}$", "i"))) .
  }
  UNION
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?gd, "{$search}", "i") && !(regex(?gd, "^{$search}", "i")) && !(regex(?gd, "{$search}$", "i"))).
  }
}
ORDER BY strlen(str(?gd))
SPQR;
  //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  $results = json_decode(file_get_contents($url),false)->results->bindings;
  foreach ($results as $nextResult) {
    echo '<a href="viewEntry.php?id=' . urlencode($nextResult->id->value) . '" class="list-group-item list-group-item-action">' . $nextResult->gd->value . ' <small>' . $nextResult->en->value . '</small></a>';
  }
  */
  echo '</tbody></table>';
}
else {
  echo 'Cuiribh rud dhan bhocsa-luirg seo.';
}
?>
      <nav class="navbar navbar-dark bg-primary fixed-bottom navbar-expand-lg">
        <a class="navbar-brand" href="index.php">Stòras Brì</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
             <a class="nav-item nav-link" href="about.html" data-toggle="tooltip" title="About this site">fios</a>
             <a class="nav-item nav-link" href="gaelicIndex.php" data-toggle="tooltip" title="About this site">indeacs</a>
             <a class="nav-item nav-link" href="random.php" data-toggle="tooltip" title="View random entry">iongnadh</a>
          </div>
        </div>
      </nav>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>

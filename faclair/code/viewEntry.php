<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Stòras-Brì</title>
  </head>
  <body style="padding-top: 20px;">
    <div class="container-fluid">
<?php
$id = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?hw ?pid ?phw ?en ?lex ?lhw ?cid ?chw ?pos ?pl ?gen ?comment
WHERE
{
  <{$id}> rdfs:label ?hw .
  OPTIONAL {
    <{$id}> :part ?pid .
    ?pid rdfs:label ?phw .
  }
  OPTIONAL {
    ?cid :part <{$id}> .
    ?cid rdfs:label ?chw .
  }
  OPTIONAL {
    GRAPH ?g {
      <{$id}> rdfs:label ?lhw .
      <{$id}> :sense ?en .
      OPTIONAL {
        <{$id}> a ?posid .
        ?posid rdfs:label ?pos .
      }
      OPTIONAL {
        <{$id}> :pl ?pl .
      }
      OPTIONAL {
        <{$id}> :gen ?gen .
      }
      OPTIONAL {
        <{$id}> rdfs:comment ?comment .
      }
    }
    ?g rdfs:label ?lex .
  }
}
SPQR;
$query = urlencode($query);
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . $query;
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
echo '<div class="card"><div class="card-body">';
echo '<h1 class="card-title">' . $results[0]->hw->value . '</h1>';
$sources = [];
foreach($results as $nextResult) {
  $lex = $nextResult->lex->value;
  if ($lex!='') {
    $sources[] = $lex;
  }
}
$sources = array_unique($sources);
echo '<div class="list-group list-group-flush">';
foreach ($sources as $nextSource) {
  echo '<div class="list-group-item">';
  echo $nextSource . ': ';
  foreach($results as $nextResult) {
    if ($nextResult->lex->value==$nextSource) {
      echo '<strong>' . $nextResult->lhw->value . '</strong> ';
      break;
    }
  }
  foreach($results as $nextResult) {
    if ($nextResult->lex->value==$nextSource) {
      if ($nextResult->pos->value!='') {
        echo ' <em>(' . $nextResult->pos->value . ')</em> ';
      }
      break;
    }
  }
  $pls = [];
  foreach($results as $nextResult) {
    if ($nextResult->lex->value==$nextSource) {
      $pl = $nextResult->pl->value;
      if ($pl!='') {
        $pls[] = $pl;
      }
    }
  }
  $pls = array_unique($pls);
  if (count($pls)>0) {
    foreach ($pls as $nextPl) {
      echo $nextPl; // what if multi plurals?
    }
    echo ' <em>(pl)</em> ';
  }
  $gens = [];
  foreach($results as $nextResult) {
    if ($nextResult->lex->value==$nextSource) {
      $gen = $nextResult->gen->value;
      if ($gen!='') {
        $gens[] = $gen;
      }
    }
  }
  $gens = array_unique($gens);
  if (count($gens)>0) {
    foreach ($gens as $nextGen) {
      echo $nextGen; // what if multi plurals?
    }
    echo ' <em>(gen)</em> ';
  }
  $ens = [];
  foreach($results as $nextResult) {
    if ($nextResult->lex->value==$nextSource) {
      $en = $nextResult->en->value;
      if ($en!='') {
        $ens[] = $en;
      }
    }
  }
  $ens = array_unique($ens);
  echo '<span class="text-muted">';
  foreach ($ens as $nextEn) {
    echo $nextEn;
    if ($nextEn != end($ens)) {
      echo ', ';
    }
  }
  echo '</span> ';
  $comments = [];
  foreach($results as $nextResult) {
    if ($nextResult->lex->value==$nextSource) {
      $comment = $nextResult->comment->value;
      if ($comment!='') {
        $comments[] = $comment;
      }
    }
  }
  $comments = array_unique($comments);
  echo '<small class="text-muted">[';
  foreach ($comments as $nextComment) {
    echo $nextComment;
    if ($nextComment != end($comments)) {
      echo '; ';
    }
  }
  echo ']</small> ';
  echo '</div>';
}
$parts = [];
foreach($results as $nextResult) {
  $pid = $nextResult->pid->value;
  if ($pid!='') {
    $parts[$pid] = $nextResult->phw->value;
  }
}
$parts = array_unique($parts);
if (count($parts)>0) {
  echo '<div class="list-group-item">See: ';
  foreach ($parts as $nextId=>$nextHw) {
    echo '<a href="viewEntry?id=' . $nextId . '">';
    echo $nextHw . '</a>';
    if ($nextHw != end($parts)) {
      echo ', ';
    }
  }
  echo '</div>';
}
$compounds = [];
foreach($results as $nextResult) {
  $cid = $nextResult->cid->value;
  if ($cid!='') {
     $compounds[$cid]= $nextResult->chw->value;
  }
}
$compounds = array_unique($compounds);
if (count($compounds)>0) {
  echo '<div class="list-group-item">Also: ';
  foreach ($compounds as $nextId=>$nextHw) {
    echo '<a href="viewEntry?id=' . $nextId . '">';
    echo $nextHw . '</a>';
    if ($nextHw != end($compounds)) {
      echo ', ';
    }
  }
  echo '</div>';
}
echo '</div>';
echo '</div></div>';
?>
    <nav class="navbar navbar-dark bg-primary fixed-bottom navbar-expand-lg">
      <a class="navbar-brand" href="index.php">Stòras-Brì</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-item nav-link" href="about.html" data-toggle="tooltip" title="About this site">fios</a>
          <a class="nav-item nav-link" href="gaelicIndex.php" data-toggle="tooltip" title="View Gaelic index">indeacs</a>
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

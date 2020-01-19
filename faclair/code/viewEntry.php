<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Stòras Brì</title>
  </head>
  <body style="padding-top: 20px;">
    <div class="container-fluid">
<?php
$id = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT ?hw ?pid ?phw ?en ?cid ?chw
WHERE
{
  OPTIONAL { <{$id}> rdfs:label ?hw . }
  OPTIONAL {
    <{$id}> :part ?pid .
    OPTIONAL { ?pid rdfs:label ?phw . }
  }
  OPTIONAL {
    ?cid :part <{$id}> .
    OPTIONAL { ?cid rdfs:label ?chw . }
  }
}
SPQR;
//$query = urlencode($query);
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . $query;
$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
echo '<div class="card"><div class="card-body">';
$hws = [];
foreach($results as $nextResult) {
  $hw = $nextResult->hw->value;
  if ($hw != '') {
    $hws[] = $hw;
  }
}
$hws = array_unique($hws);
echo '<h3 class="card-title">';
if (count($hws)>0) {
  echo implode(', ',$hws);
}
else { echo $id; }
echo '</h3>';
echo '<div class="list-group list-group-flush">';
$parts = [];
foreach($results as $nextResult) {
  $pid = $nextResult->pid->value;
  if ($pid!='') {
    $parts[$pid] = $nextResult->phw->value;
  }
}
$parts = array_unique($parts);
if (count($parts)>0) {
  echo '<div class="list-group-item">↗️ ';
  foreach ($parts as $nextId=>$nextHw) {
    echo '<a href="viewEntry?id=' . $nextId . '">';
    if ($nextHw != '') { echo $nextHw; }
    else { echo '<small>' . $nextId . '</small>'; }
    echo '</a>';
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
  echo '<div class="list-group-item">↘️ ';
  foreach ($compounds as $nextId=>$nextHw) {
    echo '<a href="viewEntry?id=' . $nextId . '">';
    if ($nextHw != '') { echo $nextHw; }
    else { echo '<small>' . $nextId . '</small>'; }
    echo '</a>';
    if ($nextHw != end($compounds)) {
      echo ', ';
    }
  }
  echo '</div>';
}
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?g ?lex ?lhw ?en ?posid ?pos ?pl ?gen ?comment
WHERE
{
  GRAPH ?g {
      <{$id}> rdfs:label ?lhw .
      OPTIONAL { <{$id}> :sense ?en . }
      OPTIONAL {
        <{$id}> a ?posid .
        OPTIONAL { ?posid rdfs:label ?pos . }
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
  OPTIONAL { ?g rdfs:label ?lex . }
}
SPQR;
//$query = urlencode($query);
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . $query;
$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
echo '<table class="table table-hover"></tbody>';
$sources = [];
foreach($results as $nextResult) {
  $g = $nextResult->g->value;
  if ($g != '') {
    $sources[] = $g;
  }
}
$sources = array_unique($sources);
foreach ($sources as $nextSource) {
  echo '<tr><td>';
  $name = $nextSource;
  foreach($results as $nextResult) {
    if ($nextResult->g->value==$nextSource) {
      $lex = $nextResult->lex->value;
      if ($lex != '') {
        $name = $lex;
      }
      break;
    }
  }
  echo $name;
  echo '</td><td><strong>';
  $lhws = [];
  foreach($results as $nextResult) {
    if ($nextResult->g->value==$nextSource) {
      $lhw = $nextResult->lhw->value;
      if ($lhw != '') {
        $lhws[] = $lhw;
      }
    }
  }
  $lhws = array_unique($lhws);
  if (count($lhws)>0) {
    echo implode(', ',$lhws);
  }
  else { echo $id; }
  echo '</strong></td><td>';
  $poss = [];
  foreach($results as $nextResult) {
    if ($nextResult->g->value==$nextSource) {
      $posid = $nextResult->posid->value;
      if ($posid != '') {
        $pos = $nextResult->pos->value;
        if ($pos != '') {
          $poss[] = $pos;
        }
        else { $poss[] = $posid; }
      }
    }
  }
  $poss = array_unique($poss);
  if (count($poss)>0) {
    echo implode(', ',$poss);
  }
  echo '</td><td><small class="text-muted">';
  $ens = [];
  foreach($results as $nextResult) {
    if ($nextResult->g->value==$nextSource) {
      $en = $nextResult->en->value;
      if ($en!='') {
        $ens[] = $en;
      }
    }
  }
  $ens = array_unique($ens);
  foreach ($ens as $nextEn) {
    echo $nextEn;
    if ($nextEn != end($ens)) {
      echo ', ';
    }
  }
  echo '</small></td><td>';
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
  echo '</td></tr>';
}

echo '</tbody></table>';

echo '</div>';
echo '</div></div>';
?>
    <nav class="navbar navbar-dark bg-primary fixed-bottom navbar-expand-lg">
      <a class="navbar-brand" href="index.php">🏛 Stòras Brì</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
          <a class="nav-item nav-link" href="about.html" data-toggle="tooltip" title="About this site">fios</a>
          <a class="nav-item nav-link" href="random.php" data-toggle="tooltip" title="View random entry">sonas</a>
        </div>
      </div>
    </nav>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>

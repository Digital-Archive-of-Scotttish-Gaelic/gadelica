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
  <body style="padding-top: 20px;">
    <div class="container-fluid">
<?php
// get generic information about lexical item for top of page
if (isset($_GET['id'])) {
  $id = $_GET['id'];
}
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?hw ?pos ?en ?pid ?phw ?cid ?chw ?pl ?gen ?comp ?vn ?vngen ?comment
WHERE
{
  GRAPH <http://faclair.ac.uk/sources/general> {
    OPTIONAL { <{$id}> rdfs:label ?hw . }
    OPTIONAL { <{$id}> :sense ?en . }
    OPTIONAL {
      <{$id}> :part ?pid .
      OPTIONAL { ?pid rdfs:label ?phw . }
    }
    OPTIONAL {
      ?cid :part <{$id}> .
      OPTIONAL { ?cid rdfs:label ?chw . }
    }
    OPTIONAL {
      <{$id}> :pl ?pl .
    }
    OPTIONAL {
      <{$id}> :gen ?gen .
    }
    OPTIONAL {
      <{$id}> :comp ?comp .
    }
    OPTIONAL {
      <{$id}> :vn ?vn .
    }
    OPTIONAL {
      <{$id}> :vngen ?vngen .
    }
    OPTIONAL {
      <{$id}> rdfs:comment ?comment .
    }
  }
  OPTIONAL {
    GRAPH <http://faclair.ac.uk/sources/general> {
      <{$id}> a ?posid .
    }
    ?posid rdfs:label ?pos .
  }
}
SPQR;
//$query = urlencode($query);
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . $query;
$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
?>
      <div class="card">
        <div class="card-body">
<?php
$hws = []; // HEADWORDS
foreach($results as $nextResult) {
  $hws[] = $nextResult->hw->value;
}
$hws = array_unique($hws);
echo '<h1 class="card-title">';
if (count($hws)>0) {
  echo implode(', ',$hws);
}
else { echo $id; } // FALLBACK
echo '</h1>';
$pos = []; // PARTS OF SPEECH
foreach($results as $nextResult) {
  $pos[] = $nextResult->pos->value;
}
$pos = array_unique($pos);
if (count($pos)>0) {
  echo '<p class="text-muted">';
  echo implode(', ', $pos);
  echo '</p>';
}
$ens = []; // ENGLISH EQUIVALENTS
foreach($results as $nextResult) {
  $ens[] = $nextResult->en->value;
}
$ens = array_unique($ens);
if (count($ens)>0) {
  echo '<p class="text-muted"><em>';
  echo implode(', ', $ens);
  echo '</em></p>';
}
// PARTS AND COMPOUNDS:
echo '<div class="list-group list-group-flush">';
$parts = [];
foreach($results as $nextResult) {
  $pid = $nextResult->pid->value;
  if ($pid!='') {
    $parts[$pid] = $nextResult->phw->value; // ASSOCIATIVE ARRAY
  }
}
$parts = array_unique($parts);
if (count($parts)>0) {
  echo '<div class="list-group-item">↗️ ';
  foreach ($parts as $nextId=>$nextHw) {
    echo '<a href="viewEntry?id=' . $nextId . '">';
    if ($nextHw != '') { echo $nextHw; }
    else { echo '<small>' . $nextId . '</small>'; } // FALLBACK
    echo '</a>';
    if ($nextHw != end($parts)) { // FUNKY!
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
    else { echo '<small>' . $nextId . '</small>'; } // FALLBACK
    echo '</a>';
    if ($nextHw != end($compounds)) {
      echo ', ';
    }
  }
  echo '</div>';
}
// GET ALTERNATIVE FORMS NEXT
$pls = [];
foreach($results as $nextResult) {
  $pl = $nextResult->pl->value;
  if ($pl!='') {
    $pls[] = $pl;
  }
}
$pls = array_unique($pls);
$gens = [];
foreach($results as $nextResult) {
  $gen = $nextResult->gen->value;
  if ($gen!='') {
    $gens[] = $gen;
  }
}
$gens = array_unique($gens);
$comps = [];
foreach($results as $nextResult) {
  $comp = $nextResult->comp->value;
  if ($comp!='') {
    $comps[] = $comp;
  }
}
$comps = array_unique($comps);
$vns = [];
foreach($results as $nextResult) {
  $vn = $nextResult->vn->value;
  if ($vn!='') {
    $vns[] = $vn;
  }
}
$vns = array_unique($vns);
$vngens = [];
foreach($results as $nextResult) {
  $vngen = $nextResult->vngen->value;
  if ($vngen!='') {
    $vngens[] = $vngen;
  }
}
$vngens = array_unique($vngens);
if (count($pls) > 0) {
  echo '<div class="list-group-item"><span class="text-muted">plural:</span> ' . implode(', ',$pls) . '</div>';
}
if (count($gens) > 0) {
  echo '<div class="list-group-item"><span class="text-muted">genitive:</span> ' . implode(', ',$gens) . '</div>';
}
if (count($comps) > 0) {
  echo '<div class="list-group-item"><span class="text-muted">comparative:</span> ' . implode(', ',$comps) . '</div>';
}
if (count($vns) > 0) {
  echo '<div class="list-group-item"><span class="text-muted">verbal noun:</span> ' . implode(', ',$vns) . '</div>';
}
if (count($vngens) > 0) {
  echo '<div class="list-group-item"><span class="text-muted">verbal noun:</span> ' . implode(', ',$vngens) . '</div>';
}
// ADMIN COMMENTS:
$comments = [];
foreach($results as $nextResult) {
  $comment = $nextResult->comment->value;
  if ($comment!='') {
    $comments[] = $comment;
  }
}
$comments = array_unique($comments);
echo '<div class="list-group-item"><small class="text-muted">Admin: ' . $id . ' | ';
echo implode(' | ',$comments);
echo '</small></div>';
echo '</div>'; // end of list group
?>
<!-- THE CAROUSEL: -->
    <div id="carouselExample" class="carousel slide" data-ride="carousel" data-interval="false">
<?php
// MAYBE DO NEXT BIT BY AJAX???
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?g ?lex ?hw #?en ?posid ?pos ?pl ?gen ?comp ?vn ?vngen ?comment ?xid ?xhw ?xen
WHERE
{
  GRAPH ?g {
      <{$id}> rdfs:label ?hw .
      OPTIONAL { <{$id}> :sense ?en . }
      #OPTIONAL {
      #  <{$id}> a ?posid .
      #  OPTIONAL { ?posid rdfs:label ?pos . }
      #}
      #OPTIONAL {
      #  <{$id}> :pl ?pl .
      #}
      #OPTIONAL {
      #  <{$id}> :gen ?gen .
      #}
      #OPTIONAL {
      #  <{$id}> :comp ?comp .
      #}
      #OPTIONAL {
      #  <{$id}> :vn ?vn .
      #}
      #OPTIONAL {
      #  <{$id}> :vngen ?vngen .
      #}
      #OPTIONAL {
      #  ?xid :part <{$id}> .
      #  ?xid rdfs:label ?xhw .
      #  ?xid :sense ?xen .
      #}
      #OPTIONAL {
      #  <{$id}> rdfs:comment ?comment .
      #}
  }
  OPTIONAL { ?g rdfs:label ?lex . }
  FILTER (?lex!=<http://faclair.ac.uk/sources/general>) .
}
SPQR;
//$query = urlencode($query);
//$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . $query;
$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
$sources = [];
foreach($results as $nextResult) {
  $g = $nextResult->g->value;
  if ($g != '') {
    $sources[] = $g;
  }
}
$sources = array_unique($sources);
echo '<ol class="carousel-indicators">';
foreach ($sources as $nextIndex=>$nextSource) {
  if (count($sources)>1) {
    echo '<li data-target="#carouselExample" data-slide-to="' . $nextIndex . '"';
    if ($nextIndex == 0) {
      echo ' class="active"';
    }
    echo ' style="filter: invert(50%);"></li>';
  }
}
echo '</ol>';
echo  '<div class="carousel-inner">';
foreach ($sources as $nextIndex=>$nextSource) {
  echo '<div class="carousel-item';
  if ($nextIndex == 0) {
    echo ' active">';
  }
  else { echo '">'; }
  echo '<div class="card"><div class="card-body">';
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
  echo '<p>From <em>' . $name . '</em>:</p><h3 class="card-title">';
  $hws = [];
  foreach($results as $nextResult) {
    if ($nextResult->g->value==$nextSource) {
      $hw = $nextResult->hw->value;
      if ($hw != '') {
        $hws[] = $hw;
      }
    }
  }
  $hws = array_unique($hws);
  if (count($hws)>0) {
    echo implode(', ',$hws);
  }
  else { echo $id; } // FALLBACK
  echo '</h3>';

  /*
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
    echo '<p>' . implode(', ',$poss) . '</p>';
  }
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
  if (count($ens)>0) {
    echo '<p><em>' . implode(', ',$ens) . '</em></p>';
  }

      echo '</small></td><td><small>';


      $pls = [];
      foreach($results as $nextResult) {
        if ($nextResult->g->value == $nextSource) {
          $pl = $nextResult->pl->value;
          if ($pl!='') {
            $pls[] = $pl;
          }
        }
      }
      $pls = array_unique($pls);
      $gens = [];
      foreach($results as $nextResult) {
        if ($nextResult->g->value == $nextSource) {
          $gen = $nextResult->gen->value;
          if ($gen!='') {
            $gens[] = $gen;
          }
        }
      }
      $gens = array_unique($gens);

      $comps = [];
      foreach($results as $nextResult) {
        if ($nextResult->g->value == $nextSource) {
          $comp = $nextResult->comp->value;
          if ($comp!='') {
            $comps[] = $comp;
          }
        }
      }
      $comps = array_unique($comps);
      $vns = [];
      foreach($results as $nextResult) {
        if ($nextResult->g->value == $nextSource) {
          $vn = $nextResult->vn->value;
          if ($vn!='') {
            $vns[] = $vn;
          }
        }
      }
      $vns = array_unique($vns);
      $vngens = [];
      foreach($results as $nextResult) {
        if ($nextResult->g->value == $nextSource) {
          $vngen = $nextResult->vngen->value;
          if ($vngen!='') {
            $vngens[] = $vngen;
          }
        }
      }
      $vngens = array_unique($vngens);
      if (count($pls) > 0) {
        echo '<span class="text-muted">pl:</span> ' . implode(', ',$pls) . '<br/>';
      }
      if (count($gens) > 0) {
        echo '<span class="text-muted">gn:</span> ' . implode(', ',$gens) . '<br/>';
      }
      if (count($comps) > 0) {
        echo '<span class="text-muted">cmp:</span> ' . implode(', ',$comps) . '<br/>';
      }
      if (count($vns) > 0) {
        echo '<span class="text-muted">vn:</span> ' . implode(', ',$vns) . '<br/>';
      }
      if (count($vngens) > 0) {
        echo '<span class="text-muted">vn gn:</span> ' . implode(', ',$vngens) . '<br/>';
      }
      echo '</small></td><td>';

  echo '<p>';
  $parts = [];
  foreach($results as $nextResult) {
    if ($nextResult->g->value == $nextSource) {
      $part = $nextResult->xid->value;
      if ($part!='') {
        $parts[] = $nextResult->xid->value;
      }
    }
  }
  $parts = array_unique($parts);
  foreach($parts as $nextPart) {
    foreach($results as $nextResult) {
      if ($nextResult->g->value == $nextSource && $nextResult->xid->value == $nextPart) {
        $xens = [];
        foreach($results as $nextResult2) {
          if ($nextResult2->xid->value == $nextPart) {
            $xens[] = $nextResult2->xen->value;
          }
        }
        $xens = array_unique($xens);
        $tooltip = implode(' | ',$xens);
        echo '<em data-toggle="tooltip" data-placement="top" title="' . $tooltip . '">' . $nextResult->xhw->value . '</em><br/>';
        break;
      }
    }
  }
  echo '</p>';


      echo '</td><td><small class="text-muted">';
      $comments = [];
      foreach($results as $nextResult) {
        if ($nextResult->g->value==$nextSource) {
          $comment = $nextResult->comment->value;
          if ($comment!='') {
            $comments[] = $comment;
          }
        }
      }
      $comments = array_unique($comments);
      echo implode('<br/>',$comments);
      echo '</small>';
*/
  echo '</div></div></div>'; // end of card-body, card and carousel item
}
echo '</div>'; // end of carousel inner
if (count($sources)>1) {
  echo '<a class="carousel-control-next" href="#carouselExample" role="button" data-slide="next">';
  echo '<span class="carousel-control-next-icon" aria-hidden="true" style="filter: invert(50%);"></span>';
  echo '<span class="sr-only">Next</span></a>';
}
?>
          </div> <!-- end of carousel -->
        </div> <!-- end of card body -->
      </div> <!-- end of card -->
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
  </body>
</html>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Stòras Brì</title>
  </head>
  <body style="padding-top: 20px;">
    <div class="container-fluid">
      <div class="card" style="max-width: 800px;">
        <div class="card-body">
<?php
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
    OPTIONAL { <{$id}> a ?pos . }
    OPTIONAL { <{$id}> :sense ?en . }
    OPTIONAL {
      <{$id}> :part ?pid .
      OPTIONAL { ?pid rdfs:label ?phw . }
    }
    OPTIONAL {
      ?cid :part <{$id}> .
      OPTIONAL { ?cid rdfs:label ?chw . }
    }
    OPTIONAL { <{$id}> :pl ?pl . }
    OPTIONAL { <{$id}> :gen ?gen . }
    OPTIONAL { <{$id}> :comp ?comp . }
    OPTIONAL { <{$id}> :vn ?vn . }
    OPTIONAL { <{$id}> :vngen ?vngen . }
    OPTIONAL { <{$id}> rdfs:comment ?comment . }
  }
}
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/faclair/code') {
  $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;

////////////
// HEADER //
////////////

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

function printPOS($posid) {
  if ($posid=='http://faclair.ac.uk/meta/MasculineNoun') { return '<span data-toggle="tooltip" data-placement="right" title="masculine noun">ainmear fireann</span>'; }
  if ($posid=='http://faclair.ac.uk/meta/FeminineNoun') { return '<span data-toggle="tooltip" data-placement="right" title="feminine noun">ainmear boireann</span>'; }
  if ($posid=='http://faclair.ac.uk/meta/Adjective') { return '<span data-toggle="tooltip" data-placement="right" title="adjective">buadhair</span>'; }
  if ($posid=='http://faclair.ac.uk/meta/Verb') { return '<span data-toggle="tooltip" data-placement="right" title="verb">gnìomhair</verb>'; }
  return $posid;
}

//////////////////
// GENERAL INFO //
//////////////////

echo '<div class="list-group list-group-flush">';
$ens = []; // ENGLISH EQUIVALENTS
foreach($results as $nextResult) {
  $en = $nextResult->en->value;
  if ($en != '') {
    $ens[] = $en;
  }
}
$ens = array_unique($ens);
if (count($ens)>0) {
  echo '<div class="list-group-item text-muted">';
  echo implode(' | ', $ens);
  echo '</div>';
}
$pos = []; // PARTS OF SPEECH
foreach($results as $nextResult) {
  $ps = $nextResult->pos->value;
  if ($ps != '') {
    $pos[] = $ps;
  }
}
$pos = array_unique($pos);
foreach ($pos as $nextpos) {
  echo '<div class="list-group-item text-muted"><em>' . printPOS($nextpos) . '</em></div>';
}
// PARTS AND COMPOUNDS:
$parts = [];
foreach($results as $nextResult) {
  $pid = $nextResult->pid->value;
  if ($pid!='') {
    $parts[$pid] = $nextResult->phw->value; // ASSOCIATIVE ARRAY
  }
}
$parts = array_unique($parts);
if (count($parts)>0) {
  echo '<div class="list-group-item"><span data-toggle="tooltip" data-placement="top" title="components">↗️</span> ';
  foreach ($parts as $nextId=>$nextHw) {
    echo '<strong><a href="viewEntry.php?id=' . $nextId . '">';
    if ($nextHw != '') { echo $nextHw; }
    else { echo '<small>' . $nextId . '</small>'; } // FALLBACK
    echo '</a></strong>';
    if ($nextHw != end($parts)) { // FUNKY!
      echo ' <span class="text-muted">|</span> ';
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
  echo '<div class="list-group-item"><span data-toggle="tooltip" data-placement="top" title="compounds">↘️</span> ';
  foreach ($compounds as $nextId=>$nextHw) {
    echo '<strong><a href="viewEntry.php?id=' . $nextId . '">';
    if ($nextHw != '') { echo $nextHw; }
    else { echo '<small>' . $nextId . '</small>'; } // FALLBACK
    echo '</a></strong>';
    if ($nextHw != end($compounds)) {
      echo ' <span class="text-muted">|</span> ';
    }
  }
  echo '</div>';
}
// ALTERNATIVE FORMS
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
  echo '<div class="list-group-item"><em class="text-muted" data-toggle="tooltip" data-placement="top" title="plural">iolra</em> <strong>' . implode('</strong> <span class="text-muted">|</span> <strong>',$pls) . '</strong></div>';
}
if (count($gens) > 0) {
  echo '<div class="list-group-item"><em class="text-muted" data-toggle="tooltip" data-placement="top" title="genitive">ginideach</em> <strong>' . implode('</strong> <span class="text-muted">|</span> <strong>',$gens) . '</strong></div>';
}
if (count($comps) > 0) {
  echo '<div class="list-group-item"><em class="text-muted" data-toggle="tooltip" data-placement="top" title="comparative">coimeasach</em> <strong>' . implode('</strong> <span class="text-muted">|</span> <strong>',$comps) . '</strong></div>';
}
if (count($vns) > 0) {
  echo '<div class="list-group-item"><em class="text-muted" data-toggle="tooltip" data-placement="top" title="verbal noun">ainmear gnìomhaireach</em> <strong>' . implode('</strong> <span class="text-muted">|</span> <strong>',$vns) . '</strong></div>';
}
if (count($vngens) > 0) {
  echo '<div class="list-group-item"><em class="text-muted" data-toggle="tooltip" data-placement="top" title="genitive verbal noun">ainmear gnìomhaireach ginideach</em> <strong>' . implode('</strong> <span class="text-muted">|</span> <strong>',$vngens) . '</strong></div>';
}
// ADMIN COMMENTS
$comments = [];
foreach($results as $nextResult) {
  $comment = $nextResult->comment->value;
  if ($comment!='') {
    $comments[] = $comment;
  }
}
$comments = array_unique($comments);
echo '<div class="list-group-item"><small class="text-muted"><span data-toggle="tooltip" data-placement="top" title="admin notes">Rianachd</span>: [' . $id . ']';
if (count($comments)>0) { echo ' | '; }
echo implode(' | ',$comments);
echo '</small></div>';
echo '</div>'; // end of list group
echo '<p>&nbsp;</p>';

//////////////
// CAROUSEL //
//////////////

echo '<div id="carouselExample" class="carousel slide" data-ride="carousel" data-interval="3000">';
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?g ?hw ?en ?pos ?pl ?gen ?comp ?vn ?vngen ?comment
WHERE
{
  GRAPH ?g {
      <{$id}> rdfs:label ?hw .
      OPTIONAL { <{$id}> :sense ?en . }
      OPTIONAL { <{$id}> a ?pos . }
      OPTIONAL { <{$id}> :pl ?pl . }
      OPTIONAL { <{$id}> :gen ?gen . }
      OPTIONAL { <{$id}> :comp ?comp . }
      OPTIONAL { <{$id}> :vn ?vn . }
      OPTIONAL { <{$id}> :vngen ?vngen . }
      OPTIONAL { <{$id}> rdfs:comment ?comment . }
  }
  FILTER (?g!=<http://faclair.ac.uk/sources/general>) .
}
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/faclair/code') {
  $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
}
$results = json_decode(file_get_contents($url),false)->results->bindings;
// SOURCES
$sources = [];
foreach($results as $nextResult) {
  $g = $nextResult->g->value;
  if ($g != '') {
    $sources[] = $g;
  }
}
$sources = array_unique($sources);
echo '<ol class="carousel-indicators">'; // little bars marking number of sources to scroll between
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

function printSource($sid) {
  if ($sid=='http://faclair.ac.uk/sources/SNH') {
    return '<a href="https://www.nature.scot/about-snh/access-information-and-services/gaelic/dictionary-gaelic-nature-words" target="_new"><img height="60px" src="SNH-logo.jpg" data-toggle="tooltip" data-placement="top" title="This is an official term from Scottish Natural Heritage‘s Dictionary of Gaelic Nature Words"/></a>';
  }
  if ($sid=='http://faclair.ac.uk/sources/FRP2013') {
    return '<img height="60px" src="https://beta.parliament.scot/-/media/images/arkscopar/splogo.svg" data-toggle="tooltip" data-placement="top" title="This is an official term from the Scottish Parliament’s Dictionary of Gaelic Terms"/>';
  }
  if ($sid=='http://faclair.ac.uk/sources/Seotal') {
    return '<a href="http://www.anseotal.org.uk/" target="_new"><img height="60px" src="http://www.anseotal.org.uk/assets/img/an-seotal-logo-animated.gif" data-toggle="tooltip" data-placement="top" title="This is an official term from Stòrlann‘s Gaelic Terminology Database"/></a>';
  }
  return $sid;
}

foreach ($sources as $nextIndex=>$nextSource) {
  echo '<div class="carousel-item';
  if ($nextIndex == 0) {
    echo ' active">';
  }
  else { echo '">'; }
  echo '<div class="card"><div class="card-body">';
  echo '<p>' . printSource($nextSource) . '</p>';
  // HEADWORDS
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
  echo '<h4 class="card-title">';
  if (count($hws)>0) {
    echo implode(', ',$hws);
  }
  else { echo $id; } // FALLBACK
  echo '</h4>';
  echo '<div class="list-group list-group-flush">';
  // ENGLISH EQUIVALENTS
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
    echo '<div class="list-group-item text-muted">' . implode(' <span class="text-muted">|</span> ',$ens) . '</div>';
  }
  //PARTS OF SPEECH
  $poss = [];
  foreach($results as $nextResult) {
    if ($nextResult->g->value==$nextSource) {
      $pos = $nextResult->pos->value;
      if ($pos != '') {
        $poss[] = $pos;
      }
    }
  }
  $poss = array_unique($poss);
  foreach ($poss as $nextpos) {
    echo '<div class="list-group-item text-muted"><em>' . printPOS($nextpos) . '</em></div>';
  }
  //ALTERNATIVES
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
    echo '<div class="list-group-item"><em class="text-muted" data-toggle="tooltip" data-placement="top" title="plural">iolra</em> <strong>' . implode('</strong> <span class="text-muted">|</span> <strong>',$pls) . '</strong></div>';
  }
  if (count($gens) > 0) {
    echo '<div class="list-group-item"><em class="text-muted" data-toggle="tooltip" data-placement="top" title="genitive">ginideach</em> <strong>' . implode('</strong> <span class="text-muted">|</span> <strong>',$gens) . '</strong></div>';
  }
  if (count($comps) > 0) {
    echo '<div class="list-group-item"><em class="text-muted" data-toggle="tooltip" data-placement="top" title="comparative">coimeasach</em> <strong>' . implode('</strong> <span class="text-muted">|</span> <strong>',$comps) . '</strong></div>';
  }
  if (count($vns) > 0) {
    echo '<div class="list-group-item"><em class="text-muted" data-toggle="tooltip" data-placement="top" title="verbal noun">ainmear gnìomhaireach</em> <strong>' . implode('</strong> <span class="text-muted">|</span> <strong>',$vns) . '</strong></div>';
  }
  if (count($vngens) > 0) {
    echo '<div class="list-group-item"><em class="text-muted" data-toggle="tooltip" data-placement="top" title="genitive verbal noun">ainmear gnìomhaireach ginideach</em> <strong>' . implode('</strong> <span class="text-muted">|</span> <strong>',$vngens) . '</strong></div>';
  }
  //NOTES
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
  if (count($comments) > 0) {
    echo '<div class="list-group-item"><small class="text-muted"><span data-toggle="tooltip" data-placement="top" title="admin notes">Rianachd</span>: ';
    echo implode(' | ',$comments);
    echo '</small></div>';
  }
  echo '</div></div></div></div>'; // end of list group, card-body, card and carousel item
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
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <nav class="navbar navbar-dark bg-primary fixed-bottom navbar-expand-lg">
        <a class="navbar-brand" href="index.php">🏛 Stòras Brì</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
          <div class="navbar-nav">
            <!--<a class="nav-item nav-link" href="about.html" data-toggle="tooltip" title="About this site">fios</a>-->
            <a class="nav-item nav-link" href="viewRandomEntry.php" data-toggle="tooltip" title="Be adventurous!">dàna</a>
          </div>
        </div>
      </nav>
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

<!doctype html>
<html lang="en">
  <head>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	  <title>Chinese</title>
	  <style>
        td { width: 50%; }
	  </style>
  </head>
  <body style="padding-top: 20px;">
    <div class="container-fluid">

<?php

function getPy($pys,$zh) {
  if ($pys[$zh]!='') {
    return $pys[$zh];
  }
  //return implode(' ',str_split($zh,3));
  $py = '';
  foreach (str_split($zh,3) as $nextZh) {
    $py .= ' ' . $pys[$nextZh];
  }
  return $py;
}

$lxml = new SimpleXMLElement('lexicon.xml',0,true);
$cxml = new SimpleXMLElement('corpus.xml',0,true);
$ens = [];
$pys = [];
$duos = [];

foreach ($cxml->ex as $nextEx) {
  $ens[(string)$nextEx['id']] = $nextEx->en;
}
foreach ($lxml->entry as $nextEntry) {
  $ens[(string)$nextEntry['id']] = $nextEntry->en;
}
foreach ($cxml->ex as $nextEx) {
  $pys[(string)$nextEx['id']] = $nextEx->py;
}
foreach ($lxml->entry as $nextEntry) {
  $pys[(string)$nextEntry['id']] = $nextEntry->py;
}
foreach ($lxml->entry as $nextEntry) {
  $duos[(string)$nextEntry['id']] = $nextEntry['duo'];
}
foreach ($cxml->ex as $nextEx) {
  $duos[(string)$nextEx['id']] = $nextEx['duo'];
}

$lessons = [1,2,3,4,5];
foreach ($lessons as $nextLesson) {
  echo '<h1>Lesson ' . $nextLesson . '</h1>';
  echo '<div class="list-group">';
  foreach ($duos as $nextId => $nextDuo) {
    if ($nextDuo==$nextLesson) {
      echo '<div class="list-group-item"><mark>' . $nextId . '</mark> ' . getPy($pys,$nextId) . '</div>';
    }
  }
  echo '</div>';
}


//echo '<p><a href="#" onclick="$(\'#py\').toggle();">[py]</a> <span id="py" style="display:none;">' . getPy($pys,$zh) . '</span></p>';
//echo '<p><a href="#" onclick="$(\'#en\').toggle();">[en]</a> <span id="en" style="display:none;">' . $ens[$zh] . '</span></p>';


?>
    </div>
  </body>
</html>

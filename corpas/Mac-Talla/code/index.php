<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Mac-Talla Songs</title>
  </head>
  <body>
    <div class="container-fluid">
      <h1>
<?php
$order = $_GET['order'];
if ($order == '') { $order = 'date'; }
echo 'Mac-Talla songs – ';
if ($order=='title') { echo 'alphabetical'; }
else { echo 'chronological'; }
?>
      </h1>
<!-- switch to other order -->
      <table class="table table-hover">
        <tbody>
<?php
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?title ?song ?issue ?date
WHERE
{
  ?song dc:isPartOf ?issueID .
  ?issueID dc:isPartOf ?volume .
  ?volume dc:isPartOf <https://dasg.ac.uk/corpus/_81> .
  ?issueID dc:title ?issue .
  ?issueID dc:date ?date .
  ?song dc:title ?title .
}
SPQR;
$query .= 'ORDER BY ?' . $order;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/MacTalla?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/corpas/Mac-Talla/code') {
  $url = 'http://localhost:3030/MacTalla?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
$songs = json_decode($json,false)->results->bindings;
foreach ($songs as $nextSong) {
  echo '<tr><td>';
  echo '<a href="showSong.php?id=';
  echo $nextSong->song->value;
  echo '">';
  echo $nextSong->title->value;
  echo '</a></td><td>';
  echo $nextSong->issue->value;
  echo '</td><td>';
  echo $nextSong->date->value;
  echo '</td></tr>';
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

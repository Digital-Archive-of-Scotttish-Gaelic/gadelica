<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <?php
$song = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
PREFIX dc: <http://purl.org/dc/terms/>
SELECT DISTINCT ?title ?issueTitle ?issueDate ?issue ?keyword ?jpg ?subject ?format
WHERE
{
  <{$song}> dc:title ?title .
  <{$song}> dc:isPartOf ?issue .
  <{$song}> dc:type ?keyword .
  <{$song}> dc:source ?jpg .
  OPTIONAL { <{$song}> dc:subject ?subject . }
  OPTIONAL { <{$song}> dc:format ?format . }
  ?issue dc:date ?issueDate .
  ?issue dc:title ?issueTitle .
}
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/MacTalla?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/corpas/Mac-Talla/code') {
  $url = 'http://localhost:3030/MacTalla?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
$facts = json_decode($json,false)->results->bindings;
echo '<title>Mac-Talla – ';
echo $facts[0]->title->value;
echo '</title>';
?>
  </head>
  <body>
    <div class="container-fluid">
      <h1><?php echo $facts[0]->title->value; ?></h1>
      <table class="table table-hover">
        <tbody>
<?php
echo '<tr><td>source:</td>';
echo '<td>' . $facts[0]->issueTitle->value . ' (' . $facts[0]->issueDate->value . ')</td></tr>';
echo '<tr><td>image:</td>';
$jpg = $facts[0]->jpg->value;
echo '<td><a href="' . $jpg . '" target="_new">' . $jpg . '</td></tr>';
echo '<tr><td>type:</td><td>';
$keywords = [];
foreach ($facts as $nextFact) {
  $kw = $nextFact->keyword->value;
  if ($kw!='') {
    $keywords[] = $kw;
  }
}
$keywords = array_unique($keywords);
foreach ($keywords as $nextKw) {
  echo '<button type="button" class="btn btn-primary">' . $nextKw . '</button> ';
}
echo '</td></tr>';
echo '<tr><td>subject:</td><td>';
$subjects = [];
foreach ($facts as $nextFact) {
  $sbj = $nextFact->subject->value;
  if ($sbj!='') {
    $subjects[] = $sbj;
  }
}
$subjects = array_unique($subjects);
echo implode($subjects,', ');
echo '</td></tr>';
echo '<tr><td>type:</td><td>';
$formats = [];
foreach ($facts as $nextFact) {
  $fm = $nextFact->format->value;
  if ($fm!='') {
    $formats[] = $fm;
  }
}
$formats = array_unique($formats);
foreach ($formats as $nextFm) {
  echo '<button type="button" class="btn btn-success">' . $nextFm . '</button> ';
}
echo '</td></tr>';


// page? author? notes?
?>
        </tbody>
      </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>

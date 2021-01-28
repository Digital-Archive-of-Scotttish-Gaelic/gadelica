<?php

require_once "../includes/htmlHeader.php";

$it = new RecursiveDirectoryIterator(INPUT_FILEPATH);
$words = [];
foreach (new RecursiveIteratorIterator($it) as $nextFile) {
  if ($nextFile->getExtension()=='xml') {
    $xml = simplexml_load_file($nextFile);
    $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    $status = $xml->xpath("/dasg:text/@status")[0];
    if ($status == 'tagged') {
      foreach ($xml->xpath("//dasg:w") as $nextWord) {
        $form = $nextWord;
        $lemma = (string)$nextWord['lemma'];
        if ($lemma=='') { $lemma = $form; }
        if (strtolower($lemma[0]) == $lemma[0]) { $form = strtolower($form); }
        $pos = (string)$nextWord['pos'];
        $words[] = $lemma . '|' . $form . '|' . $pos;
      }
    }
  }
}
echo '<p>' . count($words) . ' words in total</p>';
usort($words,'Functions::gdSort');
$lexicon = array_unique($words);
echo '<p>' . count($lexicon) . ' distinct word forms</p>';

$headwords = [];
foreach ($lexicon as $nextWord) {
  $array = explode('|',$nextWord);
  $headwords[$array[0]] .= $array[1] . ':' . $array[2] . '|';
}

echo <<<HTML
<table class="table">
  <thead>
    <tr><th>headword</th><th>forms</th></tr>
  </thead>
  <tbody>
HTML;

foreach ($headwords as $nextHw => $nextForms) {
  echo '<tr><td>' . $nextHw . '</td><td>';
  $forms = explode('|',$nextForms);
  foreach ($forms as $nextForm) {
    $bits = explode(':',$nextForm);
    if ($bits[0]!='') {
      echo $bits[0];
      if ($bits[1]!='') {
        echo ' (' . $bits[1] . ')';
      }
      echo ', ';
    }
  }
  echo '</td></tr>';
}

echo <<<HTML
        </tbody>
      </table>
HTML;

require_once "../includes/htmlFooter.php";

?>

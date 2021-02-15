<html>
<head>
<title>Lexicon</title>
</head>
<body>
  <ul>
<?php

$lxml = new SimpleXMLElement('lexicon.xml',0,true);
$cxml = new SimpleXMLElement('corpus.xml',0,true);

foreach ($cxml->ex as $nextEx) {
  echo '<p>' . $nextEx['id'] . '</p>';
}

/*
foreach ($lxml->entry as $nextEntry) {
  echo '<li>';
  echo $nextEntry['id'];
  echo '<ul>';
  foreach ($nextEntry->py as $nextPy) {
    echo '<li>' . $nextPy . '</li>';
  }
  echo '</ul>';
  echo '<ul>';
  foreach ($nextEntry->part as $nextPart) {
    echo '<li>' . $nextPart['ref'] . '</li>';
  }
  echo '</ul>';
  echo '<ul>';
  foreach ($nextEntry->en as $nextEn) {
    echo '<li>' . $nextEn . '</li>';
  }
  echo '</ul>';
  echo '</li>';
}
*/

?>
</ul>
</body>
</html>

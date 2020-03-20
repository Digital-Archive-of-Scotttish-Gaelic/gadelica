<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title>Corpas na Gàidhlig</title>
  </head>
  <body>
    <div class="container-fluid">
      <p><a href="index.php">&lt; Back to corpus index</a></p>
      <h1>Corpas na Gàidhlig lexemes</h1>
      <table class="table">
        <tbody>

<?php
$xmls = [];
$path = '../xml/';
$files = scandir($path);
foreach ($files as $nextFile) {
  if (substr($nextFile,-4)=='.xml') {
    $xmls[] = $path . $nextFile;
  }
  else if ($nextFile!='.' && $nextFile!='..') {
    $path2 = $path . $nextFile . '/';
    if (is_dir($path2)) {
      $files2 = scandir($path2);
      foreach ($files2 as $nextFile2) {
        if (substr($nextFile2,-4)=='.xml') {
          $xmls[] = $path2 . $nextFile2;
        }
        else if ($nextFile2!='.' && $nextFile2!='..') {
          $path3 = $path2 . $nextFile2 . '/';
          if (is_dir($path3)) {
            $files3 = scandir($path3);
            foreach ($files3 as $nextFile3) {
              if (substr($nextFile3,-4)=='.xml') {
                $xmls[] = $path3 . $nextFile3;
              }
            }
          }
        }
      }
    }
  }
}
$lexemes = [];
foreach ($xmls as $nextXML) {
  $xml = new SimpleXMLElement($nextXML,0,true);
  if ($xml['status'] == 'tagged') {
    $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    foreach ($xml->xpath('descendant::dasg:w') as $nextWord) {
      if ($nextWord['lemma']!='') {
        $lexemes[] = (string)$nextWord['lemma'];
      }
      else {
        $lexemes[] = (string)$nextWord;
      }
    }
  }
}


sort($lexemes);
$lexemes = array_unique($lexemes);
foreach ($lexemes as $nextLexeme) {
  echo '<tr><td>' . $nextLexeme . '</td></tr>';
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

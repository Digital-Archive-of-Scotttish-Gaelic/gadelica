<?php

require_once "includes/htmlHeader.php";

/*
echo <<<HTML
      <form>
        <input type="text" name="search"/>
        <button name="submit" type="submit">go</button>
      </form>
HTML;
*/

foreach (new DirectoryIterator(INPUT_FILEPATH) as $fileinfo) {
  if ($fileinfo->isDot()) continue;
  $filename = $fileinfo->getFilename();
  $xml = simplexml_load_file(INPUT_FILEPATH . '/' . $filename);
  foreach($xml->getDocNamespaces() as $strPrefix => $strNamespace) {
    if(strlen($strPrefix)==0) {
      $strPrefix="dasg"; //Assign an arbitrary namespace prefix.
    }
    $xml->registerXPathNamespace($strPrefix,$strNamespace);
  }
  // MM: $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/'); ?????
  $words = $xml->xpath("//dasg:w[contains(@lemma, 'craobh')]");
  foreach ($words as $word) {
    echo $word[0]. "<br/>";
  }
/* Some suggestions for next steps:
1. Output results as rows in a Bootstrap table (see Bootstrap online docs for details)
2. Three cols to start with: previous context, word, following context
3. Context defined as five elements from the set: dasg:w, dasg:pc, dasg:o
*/

}

require_once "includes/htmlFooter.php";

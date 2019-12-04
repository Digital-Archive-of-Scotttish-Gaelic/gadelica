<?php

$files = scandir('../html/');
foreach ($files as $nextFile) {
  if (strpos($nextFile, '.html')) {
    $txt = file_get_contents('../html/' . $nextFile);
    $start = strpos($txt, '<td><a style="word-break: break-word;" href="ViewEntry.aspx?ID=') + 97;
    $txt = substr($txt,$start);
    $end = strpos($txt, '</td>');
    $txt = substr($txt,0,$end);
    $end = strpos($txt, '</a>');
    $hw = substr($txt,0,$end);
    $txt = substr($txt,$end+5);
    $start = strpos($txt, '<span class="IPA"', $end) + 51;
    $end = strpos($txt, '</span>', $start);
    $ipa = substr($txt, $start, $end-$start);
    $txt = trim(substr($txt,strpos($txt,'<br />')+6)) . PHP_EOL;
    $desc = '';
    if (substr($txt,0,3) == '<i>') {
      $end = strpos($txt, '<br />');
      $desc = substr($txt, 0, $end);
      $txt = substr($txt, $end+43);
    }
    $end = strpos($txt,'<div/>');
    if ($end) {
      $txt = substr($txt,0,$end);
    }
    $ens = trim($txt);

    if (strpos($desc,'i>bua.</i>')) {
      $pos = ':Adjective';
    }
    else if (strpos($desc,'i>boir.</i>')) {
      $pos = ':FeminineNoun';
    }
    else if (strpos($desc,'i>fir.</i>')) {
      $pos = ':MasculineNoun';
    }
    else if (strpos($desc,'i>gn.</i>')) {
      $pos = ':Verb';
    }
    else if (strpos($desc,'i>sloinn.</i>')) {
      $pos = ':Surname';
    }
    else if (strpos($desc,'i>ainm.</i>')) {
      $pos = ':Name';
    }
    else {
      $pos = ':Undefined';
    }
    if (strpos($pos,'Noun') || $pos==':Surname' || $pos==':Name') {
      echo 'n:';
    }
    else if ($pos==':Adjective') {
      echo 'a:';
    }
    else if ($pos==':Verb') {
      echo 'v:';
    }
    else {
      echo 'o:';
    }
    $uri = $hw;
    $uri = str_replace(' ','_',$uri);
    $uri = str_replace('’','',$uri);
    $uri = str_replace('\'','',$uri);
    $uri = str_replace('(','',$uri);
    $uri = str_replace(')','',$uri);
    $uri = str_replace('?','',$uri);
    $uri = str_replace(',','',$uri);
    $uri = str_replace('!','',$uri);
    $uri = str_replace('-','_',$uri);
    $uri = str_replace('à','aa',$uri);
    $uri = str_replace('è','ee',$uri);
    $uri = str_replace('é','ee',$uri);
    $uri = str_replace('ì','ii',$uri);
    $uri = str_replace('ò','oo',$uri);
    $uri = str_replace('ó','oo',$uri);
    $uri = str_replace('ù','uu',$uri);
    $uri = str_replace('À','AA',$uri);
    $uri = str_replace('È','EE',$uri);
    $uri = str_replace('É','EE',$uri);
    $uri = str_replace('Ì','II',$uri);
    $uri = str_replace('Ò','OO',$uri);
    $uri = str_replace('Ó','OO',$uri);
    $uri = str_replace('Ù','UU',$uri);
    echo $uri . PHP_EOL;
    if ($pos!=':Undefined') {
      echo '  a ' . $pos . ' ;' . PHP_EOL;
    }
    //echo '  rdfs:label "' . $hw . '" ;' . PHP_EOL ;
    //echo '  :sense "' . $desc . '" ;' . PHP_EOL ;
    /*
    if ($ipa) {
      echo '  rdfs:comment "IPA: ' . $ipa . '" ;' . PHP_EOL ;
    }
    */
    if ($pos==':Adjective') {
      $check = strpos($desc,'<i>coi.</i>');
      if ($check) {
        echo '  :comp "' . substr($desc,$check+12) . '" ;' . PHP_EOL ;
      }
    }
    if (strpos($pos,'Noun') || $pos==':Surname' || $pos==':Name') {
      $check = strpos($desc,'<i>gin. ⁊ iol.</i>');
      if ($check) {
        echo '  :gen "' . substr($desc,$check+21) . '" ;' . PHP_EOL ;
        echo '  :pl "' . substr($desc,$check+21) . '" ;' . PHP_EOL ;
      }
      else {
        $check = strpos($desc,'<i>gin.</i>');
        if ($check) {
          echo '  :gen "';
          $check2 = strpos($desc,'<i>iol.</i>');
          if ($check2) {
            echo substr($desc,$check+12,$check2-$check-14) . '" ;' . PHP_EOL ;
          }
          else {
            echo substr($desc,$check+12) . '" ;' . PHP_EOL ;
          }
        }
        $check = strpos($desc,'<i>iol.</i>');
        if ($check) {
          echo '  :pl "' . substr($desc,$check+12) . '" ;' . PHP_EOL ;
        }
      }
    }
    if ($pos==':Verb') {
      $check = strpos($desc,'<i>ag.</i>');
      if ($check) {
        echo '  :vn "' . substr($desc,$check+11) . '" ;' . PHP_EOL ;
      }
    }
    echo '  rdfs:label "' . $hw . '" .' . PHP_EOL ;
    /*
    if (strpos($ens,'1')===0) {
      deNumber(substr($ens,2));
    }
    else {
      deComma($ens);
    }
    if ($desc) {echo '  rdfs:comment "Grammar: ' . $desc . '" ;' . PHP_EOL ;}
    echo '  rdfs:comment "Meaning: ' . $ens . '" .' . PHP_EOL ;
    */
    echo PHP_EOL;
  }
}

function deNumber($ens) {
  $array = explode(' 2 ',$ens);
  deComma($array[0]);
  if (strpos($array[1],' 3 ')) {
    $array = explode(' 3 ',$array[1]);
    deComma($array[0]);
    if (strpos($array[1],' 4 ')) {
      $array = explode(' 4 ',$array[1]);
      deComma($array[0]);
      if (strpos($array[1],' 5 ')) {
        $array = explode(' 5 ',$array[1]);
        deComma($array[0]);
        deComma($array[1]);
      }
      else {
        deComma($array[1]);
      }
    }
    else {
      deComma($array[1]);
    }
  }
  else {
    echo deComma($array[1]);
  }
}

function deComma($ens) {
  $array = explode(',', $ens);
  foreach ($array as $nextEn) {
    echo '  :sense "' . trim($nextEn) . '" ;' . PHP_EOL;
  }
}

?>

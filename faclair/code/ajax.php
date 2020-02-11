<?php

$search = $_GET['searchTerm'];
$snh = $_GET['snh'];
$frp = $_GET['frp'];
$seotal = $_GET['seotal'];

switch ($_REQUEST["action"]) {
  case "getEnglishResults":
    $results = getEnglishExact($search,$snh,$frp,$seotal);
    $results = array_merge($results,getEnglishPrefix($search,$snh,$frp,$seotal));
    $results = array_merge($results,getEnglishSuffix($search,$snh,$frp,$seotal));
    echo json_encode($results);
    break;
  case "getMoreEnglishResults":
    $results = getEnglishSubstring($search,$snh,$frp,$seotal);
    echo json_encode($results);
    break;
  case "getGaelicResults":
    $results = getGaelicExact($search);
    $results = array_merge($results,getGaelicPrefix($search,$snh,$frp));
    $results = array_merge($results,getGaelicSuffix($search,$snh,$frp));
    echo json_encode($results);
    break;
  case "getMoreGaelicResults":
    $results = getGaelicSubstring($search,$snh,$frp);
    echo json_encode($results);
    break;
  default:
    break;
}

function getLex($snh,$frp,$seotal) {
  $lex = '';
  if ($snh=='true') {
    if ($frp=='true') {
      if ($seotal='true') {
        $lex = 'FILTER (?lex=<http://faclair.ac.uk/sources/SNH> || ?lex=<http://faclair.ac.uk/sources/FRP2013> || ?lex=<http://faclair.ac.uk/sources/Seotal>) .';
      }
      else {
        $lex = 'FILTER (?lex=<http://faclair.ac.uk/sources/SNH> || ?lex=<http://faclair.ac.uk/sources/FRP2013>) .';
      }
    }
    else {
      $lex = 'FILTER (?lex=<http://faclair.ac.uk/sources/SNH>) .';
    }
  }
  else if ($frp=='true') {
    if ($seotal='true') {
      $lex = 'FILTER (?lex=<http://faclair.ac.uk/sources/FRP2013> || ?lex=<http://faclair.ac.uk/sources/Seotal>) .';
    }
    else {
      $lex = 'FILTER (?lex=<http://faclair.ac.uk/sources/FRP2013>) .';
    }
  }
  else if ($seotal='true') {
      $lex = 'FILTER (?lex=<http://faclair.ac.uk/sources/Seotal>) .';
  }
  return $lex;
}

function getEnglishExact($en,$snh,$frp,$seotal) {
  $lex = getLex($snh,$frp,$seotal);
  //return $lex;
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  ?id rdfs:label ?gd .
  GRAPH ?lex {
    ?id :sense ?en .
  }
  FILTER regex(?en, "^{$en}$", "i") .
  {$lex}
}
SPQR;
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  //$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicExact($gd,$snh,$frp) {
  // convert $gd to accent insensitive RE
  $lex = getLex($snh,$frp);
    $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  ?id rdfs:label ?gd .
  GRAPH ?lex {
    ?id :sense ?en .
  }
SPQR;
    $query .= ' FILTER regex(?gd, "^' . accentInsensitive($gd) . '$", "i") . ' . $lex . ' } ';
    $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    //$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

function accentInsensitive($in) {
  $rx = $in;
  $rx = str_replace('a','[aà]',$rx);
  $rx = str_replace('e','[eè]',$rx);
  $rx = str_replace('i','[iì]',$rx);
  $rx = str_replace('o','[oò]',$rx);
  $rx = str_replace('u','[uù]',$rx);
  return $rx;
}

function getEnglishPrefix($en,$snh,$frp,$seotal) {
  $lex = getLex($snh,$frp,$seotal);
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  ?id rdfs:label ?gd .
  GRAPH ?lex {
    ?id :sense ?en .
  }
  FILTER (regex(?en, "^{$en}", "i") && !(regex(?en, "{$en}$", "i"))) .
  {$lex}
}
SPQR;
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  //$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicPrefix($gd,$snh,$frp,$seotal) {
  $lex = getLex($snh,$frp,$seotal);
    $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  ?id rdfs:label ?gd .
  GRAPH ?lex {
    ?id :sense ?en .
  }
  FILTER (regex(?gd, "^{$gd}", "i") &&  !(regex(?gd, "{$gd}$", "i"))) .
  {$lex}
}
SPQR;
    $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    //$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

function getEnglishSuffix($en,$snh,$frp,$seotal) {
  $lex = getLex($snh,$frp,$seotal);
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  ?id rdfs:label ?gd .
  GRAPH ?lex {
    ?id :sense ?en .
  }
  FILTER (regex(?en, "{$en}$", "i") && !(regex(?en, "^{$en}", "i"))) .
  {$lex}
}
SPQR;
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  //$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicSuffix($gd,$snh,$frp,$seotal) {
  $lex = getLex($snh,$frp,$seotal);
    $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  ?id rdfs:label ?gd .
  GRAPH ?lex {
    ?id :sense ?en .
  }
  FILTER (regex(?gd, "{$gd}$", "i") &&  !(regex(?gd, "^{$gd}", "i"))) .
  {$lex}
}
SPQR;
    $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    //$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

function getEnglishSubstring($en,$snh,$frp,$seotal) {
  $lex = getLex($snh,$frp,$seotal);
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  ?id rdfs:label ?gd .
  GRAPH ?lex {
    ?id :sense ?en .
  }
  FILTER (regex(?en, "{$en}", "i") && !(regex(?en, "^{$en}", "i")) && !(regex(?en, "{$en}$", "i"))) .
  {$lex}
}
SPQR;
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  //$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicSubstring($gd,$snh,$frp,$seotal) {
  $lex = getLex($snh,$frp,$seotal);
    $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  ?id rdfs:label ?gd .
  GRAPH ?lex {
    ?id :sense ?en .
  }
  FILTER (regex(?gd, "{$gd}", "i") && !(regex(?gd, "^{$gd}", "i"))  && !(regex(?gd, "{$gd}$", "i"))) .
  {$lex}
}
SPQR;
    $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    //$url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

?>

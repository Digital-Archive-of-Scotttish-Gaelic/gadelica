<?php

switch ($_REQUEST["action"]) {
  case "getEnglishResults":
    $results = getEnglishExact();
    echo json_encode($results);
    break;
  case "getMoreEnglishResults":
    $results = getEnglishPrefix();
    echo json_encode($results);
    break;
  case "getEvenMoreEnglishResults":
    $results = getEnglishSuffix();
    echo json_encode($results);
    break;
  case "getEvenEvenMoreEnglishResults":
    $results = getEnglishSubstring();
    echo json_encode($results);
    break;
  case "getGaelicResults":
    $results = getGaelicExact();
    echo json_encode($results);
    break;
  case "getMoreGaelicResults":
    $results = getGaelicPrefix();
    echo json_encode($results);
    break;
  case "getEvenMoreGaelicResults":
    $results = getGaelicSuffix();
    echo json_encode($results);
    break;
  case "getEvenEvenMoreGaelicResults":
    $results = getGaelicSubstring();
    echo json_encode($results);
    break;
  default:
    break;
}

function getLex() {
  $lex = [];
  if ($_GET['snh']=='true') {
    $lex[] = '?lex=<http://faclair.ac.uk/sources/SNH>';
  }
  if ($_GET['frp']=='true') {
    $lex[] = '?lex=<http://faclair.ac.uk/sources/FRP2013>';
  }
  if ($_GET['seotal']=='true') {
    $lex[] = '?lex=<http://faclair.ac.uk/sources/Seotal>';
  }
  if ($_GET['dwelly']=='true') {
    $lex[] = '?lex=<http://faclair.ac.uk/sources/Dwelly>';
  }
  if ($_GET['others']=='true') {
    $lex[] = '?lex=<http://faclair.ac.uk/sources/general>';
  }
  $str = implode(' || ', $lex);
  return 'FILTER (' . $str . ') .';
}

function getQueryPrefix() {
  return <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  GRAPH <http://faclair.ac.uk/sources/general> {
    ?id rdfs:label ?gd .
  }
  GRAPH ?lex {
    ?id :sense ?en .
  }
SPQR;
}

function getEnglishExact() {
  $lex = getLex();
  $en = $_GET['searchTerm'];
  $query = getQueryPrefix() . <<<SPQR
  FILTER regex(?en, "^{$en}$", "i") .
  {$lex}
}
ORDER BY strlen(?gd)
SPQR;
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  if (getcwd()=='/Users/mark/Sites/gadelica/faclair/code') {
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  }
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicExact() {
  $lex = getLex();
  $gd = $_GET['searchTerm'];
  $query = getQueryPrefix() . 'FILTER regex(?gd, "^' . accentInsensitive($gd) . '$", "i") .' . $lex . '}';
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query); // maybe this is problem???? maybe not url encode $gd
  if (getcwd()=='/Users/mark/Sites/gadelica/faclair/code') {
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  }
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function accentInsensitive($in) {
  $rx = $in;
  //$rx = str_replace('u','[uù]',$rx); //sùil
  //$rx = str_replace('e','[eèé]',$rx); // lèirmheas
  //$rx = str_replace('a','[aà]',$rx); // làmh
  //$rx = str_replace('i','[iì]',$rx); //gnìomh
  //$rx = str_replace('o','[oòó]',$rx); // mòr
  $rx = str_replace('u','[u\u00f9]',$rx); //sùil
  $rx = str_replace('e','[e\u00e8\u00e9]',$rx); //lèirmheas
  $rx = str_replace('a','[a\u00e0]',$rx); // làmh
  $rx = str_replace('i','[i\u00ec]',$rx); //gnìomh
  $rx = str_replace('o','[o\u00f2\u00f3]',$rx); // mòr
  return $rx;
}

function getEnglishPrefix() {
  $lex = getLex();
  $en = $_GET['searchTerm'];
  $query = getQueryPrefix() . <<<SPQR
  FILTER (regex(?en, "^{$en}", "i") && !(regex(?en, "{$en}$", "i"))) .
  {$lex}
}
ORDER BY strlen(?gd)
SPQR;
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  if (getcwd()=='/Users/mark/Sites/gadelica/faclair/code') {
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  }
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicPrefix() {
  $lex = getLex();
  $gd = $_GET['searchTerm'];
  $query = getQueryPrefix() . 'FILTER (regex(?gd, "^' . accentInsensitive($gd) . '", "i") && !(regex(?gd, "' . accentInsensitive($gd) . '$", "i"))) .' . $lex . ' }';
  $query .= 'ORDER BY strlen(?gd)';
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  if (getcwd()=='/Users/mark/Sites/gadelica/faclair/code') {
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  }
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function getEnglishSuffix() {
  $lex = getLex();
  $en = $_GET['searchTerm'];
  $query = getQueryPrefix() . <<<SPQR
  FILTER (regex(?en, "{$en}$", "i") && !(regex(?en, "^{$en}", "i"))) .
  {$lex}
}
ORDER BY strlen(?gd)
SPQR;
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  if (getcwd()=='/Users/mark/Sites/gadelica/faclair/code') {
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  }
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicSuffix() {
  $lex = getLex();
  $gd = $_GET['searchTerm'];
  $query = getQueryPrefix() . 'FILTER (regex(?gd, "' . accentInsensitive($gd) . '$", "i") && !(regex(?gd, "^' . accentInsensitive($gd) . '", "i"))) .' . $lex . ' }';
  $query .= 'ORDER BY strlen(?gd)';
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  if (getcwd()=='/Users/mark/Sites/gadelica/faclair/code') {
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  }
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function getEnglishSubstring() {
  $lex = getLex();
  $en = $_GET['searchTerm'];
  $query = getQueryPrefix() . <<<SPQR
  FILTER (regex(?en, "{$en}", "i") && !(regex(?en, "^{$en}", "i")) && !(regex(?en, "{$en}$", "i"))) .
  {$lex}
}
ORDER BY strlen(?gd)
SPQR;
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  if (getcwd()=='/Users/mark/Sites/gadelica/faclair/code') {
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  }
  return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicSubstring() {
  $lex = getLex();
  $gd = $_GET['searchTerm'];
  $query = getQueryPrefix() . 'FILTER (regex(?gd, "' . accentInsensitive($gd) . '", "i") && !(regex(?gd, "^' . accentInsensitive($gd) . '", "i")) && !(regex(?gd, "' . accentInsensitive($gd) . '$", "i"))) .' . $lex . ' }';
  $query .= 'ORDER BY strlen(?gd)';
  $url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  if (getcwd()=='/Users/mark/Sites/gadelica/faclair/code') {
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  }
  return json_decode(file_get_contents($url),false)->results->bindings;
}

?>

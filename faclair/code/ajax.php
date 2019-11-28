<?php

switch ($_REQUEST["action"]) {
  case "getEnglishResults":
    $search = $_GET['searchTerm'];
    $results = getEnglishExact($search);
    $results = array_merge($results,getEnglishPrefix($search));
    $results = array_merge($results,getEnglishSuffix($search));
    echo json_encode($results);
    break;
  case "getMoreEnglishResults":
    $search = $_GET['searchTerm'];
    $results = getEnglishSubstring($search);
    echo json_encode($results);
    break;
  case "getGaelicResults":
    $search = $_GET['searchTerm'];
    $results = getGaelicExact($search);
    $results = array_merge($results,getGaelicPrefix($search));
    $results = array_merge($results,getGaelicSuffix($search));
    echo json_encode($results);
    break;
  case "getMoreGaelicResults":
    $search = $_GET['searchTerm'];
    $results = getGaelicSubstring($search);
    echo json_encode($results);
    break;
  default:
    break;
}

function getEnglishExact($en) {
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
}
SPQR;
    //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicExact($gd) {
  // convert $gd to accent insensitive RE
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
    $query .= ' FILTER regex(?gd, "^' . accentInsensitive($gd) . '$", "i") . } ';
    //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
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

function getEnglishPrefix($en) {
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
}
SPQR;
    //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicPrefix($gd) {
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
}
SPQR;
    //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

function getEnglishSuffix($en) {
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
}
SPQR;
    //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicSuffix($gd) {
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
}
SPQR;
    //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

function getEnglishSubstring($en) {
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
}
SPQR;
    //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

function getGaelicSubstring($gd) {
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
}
SPQR;
    //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
    $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
    return json_decode(file_get_contents($url),false)->results->bindings;
}

?>

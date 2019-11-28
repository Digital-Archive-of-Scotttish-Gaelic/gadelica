


if ($search != '') {
  echo '<table class="table table-hover"><tbody>';
  // exact English/Gaelic search (case insensitive):
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER regex(?en, "^{$search}$", "i") .
  }
  UNION
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER regex(?gd, "^{$search}$", "i") .
  }
}
SPQR;
  //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  $results = json_decode(file_get_contents($url),false)->results->bindings;
  function showResults($rs) {
    $ids = [];
    foreach ($rs as $nextResult) {
      $ids[] = $nextResult->id->value;
    }
    $ids = array_unique($ids);
    foreach ($ids as $nextid) {
      $hw = '';
      $ens = [];
      foreach ($rs as $nextResult) {
        if ($nextResult->id->value == $nextid) {
          $hw = $nextResult->gd->value;
          $ens[] = $nextResult->en->value;
        }
      }
      echo '<tr><td><a href="viewEntry.php?id=' . urlencode($nextid) . '">' . $hw . '</a></td>';
      echo '<td>';
      $enstr = '';
      foreach ($ens as $nexten) {
        $enstr .= $nexten . ', ';
      }
      $enstr = trim($enstr, ', ');
      echo $enstr;
      echo '</td></tr>';
    }
  }
  showResults($results);
  // prefix match:
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?en, "^{$search}", "i") && !(regex(?en, "{$search}$", "i"))) .
  }
  UNION
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?gd, "^{$search}", "i") && !(regex(?gd, "{$search}$", "i"))).
  }
}
ORDER BY strlen(str(?gd))
SPQR;
  //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  $results = json_decode(file_get_contents($url),false)->results->bindings;
  showResults($results);
  //suffix match:
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?en, "{$search}$", "i") && !(regex(?en, "^{$search}", "i"))) .
  }
  UNION
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?gd, "{$search}$", "i") && !(regex(?gd, "^{$search}", "i"))).
  }
}
ORDER BY strlen(str(?gd))
SPQR;
  //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  $results = json_decode(file_get_contents($url),false)->results->bindings;
  showResults($results);
  // substring match:
  $query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT DISTINCT ?id ?gd ?en
WHERE
{
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?en, "{$search}", "i") && !(regex(?en, "^{$search}", "i")) && !(regex(?en, "{$search}$", "i"))) .
  }
  UNION
  {
    ?id rdfs:label ?gd .
    GRAPH ?lex {
      ?id :sense ?en .
    }
    FILTER (regex(?gd, "{$search}", "i") && !(regex(?gd, "^{$search}", "i")) && !(regex(?gd, "{$search}$", "i"))).
  }
}
ORDER BY strlen(str(?gd))
SPQR;
  //$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
  $url = 'http://localhost:3030/Faclair?output=json&query=' . urlencode($query);
  $results = json_decode(file_get_contents($url),false)->results->bindings;
  showResults($results);
  echo '</tbody></table>';
}
else {
  echo 'Cuiribh rud dhan bhocsa-luirg seo.';
}

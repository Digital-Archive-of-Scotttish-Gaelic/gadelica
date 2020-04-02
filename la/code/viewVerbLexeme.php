<div class="card" style="max-width: 800px;">
  <div class="card-body">
<?php
$id = $_GET['id'];
$query = <<<SPQR
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX : <http://faclair.ac.uk/meta/>
SELECT ?hw ?inf ?inff ?pfinf ?pfinff ?en
      ?p1s ?p1sf ?p2s ?p2sf ?p3s ?p3sf ?p1p ?p1pf ?p2p ?p2pf ?p3p ?p3pf
      ?i1s ?i1sf ?i2s ?i2sf ?i3s ?i3sf ?i1p ?i1pf ?i2p ?i2pf ?i3p ?i3pf
      ?f1s ?f1sf ?f2s ?f2sf ?f3s ?f3sf ?f1p ?f1pf ?f2p ?f2pf ?f3p ?f3pf
      ?pf1s ?pf1sf ?pf2s ?pf2sf ?pf3s ?pf3sf ?pf1p ?pf1pf ?pf2p ?pf2pf ?pf3p ?pf3pf
      ?ppf1s ?ppf1sf ?ppf2s ?ppf2sf ?ppf3s ?ppf3sf ?ppf1p ?ppf1pf ?ppf2p ?ppf2pf ?ppf3p ?ppf3pf
      ?fpf1s ?fpf1sf ?fpf2s ?fpf2sf ?fpf3s ?fpf3sf ?fpf1p ?fpf1pf ?fpf2p ?fpf2pf ?fpf3p ?fpf3pf
      ?sp1s ?sp1sf ?sp2s ?sp2sf ?sp3s ?sp3sf ?sp1p ?sp1pf ?sp2p ?sp2pf ?sp3p ?sp3pf
      ?si1s ?si1sf ?si2s ?si2sf ?si3s ?si3sf ?si1p ?si1pf ?si2p ?si2pf ?si3p ?si3pf
      ?sppf1s ?sppf1sf ?sppf2s ?sppf2sf ?sppf3s ?sppf3sf ?sppf1p ?sppf1pf ?sppf2p ?sppf2pf ?sppf3p ?sppf3pf
      ?spf1s ?spf1sf ?spf2s ?spf2sf ?spf3s ?spf3sf ?spf1p ?spf1pf ?spf2p ?spf2pf ?spf3p ?spf3pf
      #?stem ?stemf ?suffix ?prefix
WHERE
{
  <{$id}> rdfs:label ?hw .
  <{$id}> :sense ?en .
  ?inf :stem <{$id}> ; a :Infinitive ; rdfs:label ?inff .
  ?p1s :stem <{$id}> ; a :FirstSingular ; rdfs:label ?p1sf .
  ?p2s :stem <{$id}> ; a :SecondSingular ; rdfs:label ?p2sf .
  ?p3s :stem <{$id}> ; a :ThirdSingular ; rdfs:label ?p3sf .
  ?p1p :stem <{$id}> ; a :FirstPlural ; rdfs:label ?p1pf .
  ?p2p :stem <{$id}> ; a :SecondPlural ; rdfs:label ?p2pf .
  ?p3p :stem <{$id}> ; a :ThirdPlural ; rdfs:label ?p3pf .
  ?impStem :stem <{$id}> ; a :Imperfect .
  ?i1s :stem ?impStem ; a :FirstSingular ; rdfs:label ?i1sf .
  ?i2s :stem ?impStem ; a :SecondSingular ; rdfs:label ?i2sf .
  ?i3s :stem ?impStem ; a :ThirdSingular ; rdfs:label ?i3sf .
  ?i1p :stem ?impStem ; a :FirstPlural ; rdfs:label ?i1pf .
  ?i2p :stem ?impStem ; a :SecondPlural ; rdfs:label ?i2pf .
  ?i3p :stem ?impStem ; a :ThirdPlural ; rdfs:label ?i3pf .
  OPTIONAL {
  ?futStem :stem <{$id}> ; a :Future .
  ?f1s :stem ?futStem ; a :FirstSingular ; rdfs:label ?f1sf .
  ?f2s :stem ?futStem ; a :SecondSingular ; rdfs:label ?f2sf .
  ?f3s :stem ?futStem ; a :ThirdSingular ; rdfs:label ?f3sf .
  ?f1p :stem ?futStem ; a :FirstPlural ; rdfs:label ?f1pf .
  ?f2p :stem ?futStem ; a :SecondPlural ; rdfs:label ?f2pf .
  ?f3p :stem ?futStem ; a :ThirdPlural ; rdfs:label ?f3pf .
  }
  OPTIONAL {
    ?perfStem :stem <{$id}> ; a :Perfect .
    ?pfinf :stem ?perfStem ; a :Infinitive ; rdfs:label ?pfinff .
    ?pf1s :stem ?perfStem ; a :FirstSingular ; rdfs:label ?pf1sf .
    ?pf2s :stem ?perfStem ; a :SecondSingular ; rdfs:label ?pf2sf .
    ?pf3s :stem ?perfStem ; a :ThirdSingular ; rdfs:label ?pf3sf .
    ?pf1p :stem ?perfStem ; a :FirstPlural ; rdfs:label ?pf1pf .
    ?pf2p :stem ?perfStem ; a :SecondPlural ; rdfs:label ?pf2pf .
    ?pf3p :stem ?perfStem ; a :ThirdPlural ; rdfs:label ?pf3pf .
  OPTIONAL {
    ?pperfStem :stem ?perfStem ; a :PluPerfect .
    ?ppf1s :stem ?pperfStem ; a :FirstSingular ; rdfs:label ?ppf1sf .
    ?ppf2s :stem ?pperfStem ; a :SecondSingular ; rdfs:label ?ppf2sf .
    ?ppf3s :stem ?pperfStem ; a :ThirdSingular ; rdfs:label ?ppf3sf .
    ?ppf1p :stem ?pperfStem ; a :FirstPlural ; rdfs:label ?ppf1pf .
    ?ppf2p :stem ?pperfStem ; a :SecondPlural ; rdfs:label ?ppf2pf .
    ?ppf3p :stem ?pperfStem ; a :ThirdPlural ; rdfs:label ?ppf3pf .
  }
  OPTIONAL {
    ?fperfStem :stem ?perfStem ; a :FuturePerfect .
    ?fpf1s :stem ?fperfStem ; a :FirstSingular ; rdfs:label ?fpf1sf .
    ?fpf2s :stem ?fperfStem ; a :SecondSingular ; rdfs:label ?fpf2sf .
    ?fpf3s :stem ?fperfStem ; a :ThirdSingular ; rdfs:label ?fpf3sf .
    ?fpf1p :stem ?fperfStem ; a :FirstPlural ; rdfs:label ?fpf1pf .
    ?fpf2p :stem ?fperfStem ; a :SecondPlural ; rdfs:label ?fpf2pf .
    ?fpf3p :stem ?fperfStem ; a :ThirdPlural ; rdfs:label ?fpf3pf .
  }
  OPTIONAL {
    ?sppf1s :stem ?pfinf ; a :FirstSingular ; rdfs:label ?sppf1sf .
    ?sppf2s :stem ?pfinf ; a :SecondSingular ; rdfs:label ?sppf2sf .
    ?sppf3s :stem ?pfinf ; a :ThirdSingular ; rdfs:label ?sppf3sf .
    ?sppf1p :stem ?pfinf ; a :FirstPlural ; rdfs:label ?sppf1pf .
    ?sppf2p :stem ?pfinf ; a :SecondPlural ; rdfs:label ?sppf2pf .
    ?sppf3p :stem ?pfinf ; a :ThirdPlural ; rdfs:label ?sppf3pf .
  }
  OPTIONAL {
    ?perfSubjStem :stem ?perfStem ; a :PerfectSubjunctive .
    ?spf1s :stem ?perfSubjStem ; a :FirstSingular ; rdfs:label ?spf1sf .
    ?spf2s :stem ?perfSubjStem ; a :SecondSingular ; rdfs:label ?spf2sf .
    ?spf3s :stem ?perfSubjStem ; a :ThirdSingular ; rdfs:label ?spf3sf .
    ?spf1p :stem ?perfSubjStem ; a :FirstPlural ; rdfs:label ?spf1pf .
    ?spf2p :stem ?perfSubjStem ; a :SecondPlural ; rdfs:label ?spf2pf .
    ?spf3p :stem ?perfSubjStem ; a :ThirdPlural ; rdfs:label ?spf3pf .
  }


  }

  OPTIONAL {
    ?subjStem :stem <{$id}> ; a :Subjunctive .
    ?sp1s :stem ?subjStem ; a :FirstSingular ; rdfs:label ?sp1sf .
    ?sp2s :stem ?subjStem ; a :SecondSingular ; rdfs:label ?sp2sf .
    ?sp3s :stem ?subjStem ; a :ThirdSingular ; rdfs:label ?sp3sf .
    ?sp1p :stem ?subjStem ; a :FirstPlural ; rdfs:label ?sp1pf .
    ?sp2p :stem ?subjStem ; a :SecondPlural ; rdfs:label ?sp2pf .
    ?sp3p :stem ?subjStem ; a :ThirdPlural ; rdfs:label ?sp3pf .
  }
  OPTIONAL {
    ?si1s :stem ?inf ; a :FirstSingular ; rdfs:label ?si1sf .
    ?si2s :stem ?inf ; a :SecondSingular ; rdfs:label ?si2sf .
    ?si3s :stem ?inf ; a :ThirdSingular ; rdfs:label ?si3sf .
    ?si1p :stem ?inf ; a :FirstPlural ; rdfs:label ?si1pf .
    ?si2p :stem ?inf ; a :SecondPlural ; rdfs:label ?si2pf .
    ?si3p :stem ?inf ; a :ThirdPlural ; rdfs:label ?si3pf .
  }



  OPTIONAL {
    <{$id}> :stem ?stem .
    ?stem rdfs:label ?stemf .
    OPTIONAL {
      <{$id}> :suffix ?suffix .
    }
    OPTIONAL {
      <{$id}> :prefix ?prefix .
    }
  }
}
SPQR;
$url = 'https://daerg.arts.gla.ac.uk/fuseki/Faclair?output=json&query=' . urlencode($query);
if (getcwd()=='/Users/mark/Sites/gadelica/la/code') {
  $url = 'http://localhost:3030/Latin?output=json&query=' . urlencode($query);
}
$json = file_get_contents($url);
$results = json_decode($json,false)->results->bindings;
echo '<h1 class="card-title">';
echo $results[0]->hw->value ;
echo '</h1>';
echo '<div class="list-group list-group-flush">';
echo '<div class="list-group-item text-muted"><em>verb</em></div>';
$ens = []; // ENGLISH EQUIVALENTS
foreach($results as $nextResult) {
  $en = $nextResult->en->value;
  if ($en != '') {
    $ens[] = $en;
  }
}
$ens = array_unique($ens);
if (count($ens)>0) {
  echo '<div class="list-group-item">';
  echo implode(' | ', $ens);
  echo '</div>';
}
$stem = $results[0]->stem->value;
if ($stem!='') {
  echo '<div class="list-group-item">';
  echo '<em class="text-muted">roots</em> ';
  echo $results[0]->prefix->value;
  echo ' ';
  echo '<strong><a href="viewLexeme.php?id=' . $stem . '">' . $results[0]->stemf->value . '</a></strong>';
  echo ' ';
  echo $results[0]->suffix->value;
  echo '</div>';
}
echo '<div class="list-group-item">';
echo '<em class="text-muted">infinitives</em> ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->inf->value . '\');">' . $results[0]->inff->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->pfinf->value . '\');">' . $results[0]->pfinff->value . '</a></strong>';
echo '</div>';
echo '<div class="list-group-item">';
echo '<em class="text-muted">present indicative active</em> ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->p1s->value . '\');">' . $results[0]->p1sf->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->p2s->value . '\');">' . $results[0]->p2sf->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->p3s->value . '\');">' . $results[0]->p3sf->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->p1p->value . '\');">' . $results[0]->p1pf->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->p2p->value . '\');">' . $results[0]->p2pf->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->p3p->value . '\');">' . $results[0]->p3pf->value . '</a></strong>';
echo '</div>';
echo '<div class="list-group-item">';
echo '<em class="text-muted">imperfect indicative active</em> ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->i1s->value . '\');">' . $results[0]->i1sf->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->i2s->value . '\');">' . $results[0]->i2sf->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->i3s->value . '\');">' . $results[0]->i3sf->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->i1p->value . '\');">' . $results[0]->i1pf->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->i2p->value . '\');">' . $results[0]->i2pf->value . '</a></strong>, ';
echo '<strong><a href="#" onclick="loadForm(\'' . $results[0]->i3p->value . '\');">' . $results[0]->i3pf->value . '</a></strong>';
echo '</div>';
echo '<div class="list-group-item">';
echo '<em class="text-muted">future indicative active</em> ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->f1s->value . '">' . $results[0]->f1sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->f2s->value . '">' . $results[0]->f2sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->f3s->value . '">' . $results[0]->f3sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->f1p->value . '">' . $results[0]->f1pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->f2p->value . '">' . $results[0]->f2pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->f3p->value . '">' . $results[0]->f3pf->value . '</a></strong>';
echo '</div>';
echo '<div class="list-group-item">';
echo '<em class="text-muted">perfect indicative active</em> ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->pf1s->value . '">' . $results[0]->pf1sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->pf2s->value . '">' . $results[0]->pf2sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->pf3s->value . '">' . $results[0]->pf3sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->pf1p->value . '">' . $results[0]->pf1pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->pf2p->value . '">' . $results[0]->pf2pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->pf3p->value . '">' . $results[0]->pf3pf->value . '</a></strong>';
echo '</div>';
echo '<div class="list-group-item">';
echo '<em class="text-muted">pluperfect indicative active</em> ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->ppf1s->value . '">' . $results[0]->ppf1sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->ppf2s->value . '">' . $results[0]->ppf2sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->ppf3s->value . '">' . $results[0]->ppf3sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->ppf1p->value . '">' . $results[0]->ppf1pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->ppf2p->value . '">' . $results[0]->ppf2pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->ppf3p->value . '">' . $results[0]->ppf3pf->value . '</a></strong>';
echo '</div>';
echo '<div class="list-group-item">';
echo '<em class="text-muted">future perfect indicative active</em> ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->fpf1s->value . '">' . $results[0]->fpf1sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->fpf2s->value . '">' . $results[0]->fpf2sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->fpf3s->value . '">' . $results[0]->fpf3sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->fpf1p->value . '">' . $results[0]->fpf1pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->fpf2p->value . '">' . $results[0]->fpf2pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->fpf3p->value . '">' . $results[0]->fpf3pf->value . '</a></strong>';
echo '</div>';
echo '<div class="list-group-item">';
echo '<em class="text-muted">present subjunctive active</em> ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sp1s->value . '">' . $results[0]->sp1sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sp2s->value . '">' . $results[0]->sp2sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sp3s->value . '">' . $results[0]->sp3sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sp1p->value . '">' . $results[0]->sp1pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sp2p->value . '">' . $results[0]->sp2pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sp3p->value . '">' . $results[0]->sp3pf->value . '</a></strong>';
echo '</div>';
echo '<div class="list-group-item">';
echo '<em class="text-muted">imperfect subjunctive active</em> ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->si1s->value . '">' . $results[0]->si1sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->si2s->value . '">' . $results[0]->si2sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->si3s->value . '">' . $results[0]->si3sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->si1p->value . '">' . $results[0]->si1pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->si2p->value . '">' . $results[0]->si2pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->si3p->value . '">' . $results[0]->si3pf->value . '</a></strong>';
echo '</div>';
echo '<div class="list-group-item">';
echo '<em class="text-muted">pluperfect subjunctive active</em> ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sppf1s->value . '">' . $results[0]->sppf1sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sppf2s->value . '">' . $results[0]->sppf2sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sppf3s->value . '">' . $results[0]->sppf3sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sppf1p->value . '">' . $results[0]->sppf1pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sppf2p->value . '">' . $results[0]->sppf2pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->sppf3p->value . '">' . $results[0]->sppf3pf->value . '</a></strong>';
echo '</div>';
echo '<div class="list-group-item">';
echo '<em class="text-muted">perfect subjunctive active</em> ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->spf1s->value . '">' . $results[0]->spf1sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->spf2s->value . '">' . $results[0]->spf2sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->spf3s->value . '">' . $results[0]->spf3sf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->spf1p->value . '">' . $results[0]->spf1pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->spf2p->value . '">' . $results[0]->spf2pf->value . '</a></strong>, ';
echo '<strong><a href="viewForm.php?id=' . $results[0]->spf3p->value . '">' . $results[0]->spf3pf->value . '</a></strong>';
echo '</div>';

echo '</div>'; // end of list-group
?>
  </div> <!-- end of card-body-->
</div> <!-- end of card -->

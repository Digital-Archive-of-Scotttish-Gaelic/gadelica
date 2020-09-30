<?php

namespace models;

class CorpusModel {

    public $textModels = []; // an array of TextModels

    public function __construct() {
        $spqr = new SPARQLQuery();
        $query = <<<SPQR
          SELECT DISTINCT ?uri ?rank ?title ?xml ?part ?writer ?surname ?forenames ?nickname ?date
          WHERE
          {
            ?uri dc:identifier ?rank .
            ?uri dc:title ?title .
            ?uri dc:date ?date .
            OPTIONAL {
              ?uri dc:creator ?writer .
              OPTIONAL {
                ?writer :surnameGD ?surname .
                ?writer :forenamesGD ?forenames .
                OPTIONAL {
                  ?writer :nickname ?nickname .
                }
              }
            }
            OPTIONAL { ?uri :xml ?xml . }
            OPTIONAL { ?part dc:isPartOf ?uri . }
            FILTER NOT EXISTS { ?uri dc:isPartOf ?superuri . }
          }
          ORDER BY ?rank
SPQR;
          $results = $spqr->getQueryResults($query);
          //$texts = [];
          //$textList = [];
          foreach ($results as $nextResult) {
            $this->textModels[] = new TextModel($nextResult->uri->value);
            //$texts[] = $nextResult->uri->value;
          }

          /*
          $texts = array_unique($texts);
          foreach ($texts as $nextText) {
            $rank = '';
            $title = '';
            foreach ($results as $nextResult) {
              if ($nextResult->uri->value==$nextText) {
                $rank = $nextResult->rank->value;
                $title = $nextResult->title->value;
                break;
              }
            }
            $textList[$rank]["title"] = $title;
            $textList[$rank]["textUri"] = $nextText;
            $writers = [];
            foreach ($results as $nextResult) {
              if ($nextResult->uri->value==$nextText) {
                $nextWriter = $nextResult->writer->value;
                if ($nextWriter!='') {
                  $writers[] = $nextWriter;
                }
              }
            }
            $writers = array_unique($writers);
            $writerNum = 0;
            foreach ($writers as $nextWriter) {
              $writerNum++;
              if (substr($nextWriter,0,8)=='https://') {
                foreach ($results as $nextResult) {
                  if ($nextResult->writer->value==$nextWriter) {
                    $textList[$rank]["writer"][$writerNum]["writerUri"] = $nextWriter;
                    $textList[$rank]["writer"][$writerNum]["forenames"] = $nextResult->forenames->value;
                    $textList[$rank]["writer"][$writerNum]["surname"] = $nextResult->surname->value;
                    if ($nextResult->nickname->value!='') {
                      $textList[$rank]["writer"][$writerNum]["nickname"] = $nextResult->nickname->value;
                    }
                    break;
                  }
                }
              }
              else {
                $textList[$rank]["writer"][$writerNum]["surname"] = $nextWriter;
              }
            }
            foreach ($results as $nextResult) {
              if ($nextResult->uri->value==$nextText) {
                $textList[$rank]["date"] = $nextResult->date->value;
                break;
              }
            }
          }
          $this->texts = $textList;
          */
    }

}

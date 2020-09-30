<?php

namespace controllers;
use models, views;

class CorpusController {

    public $corpus = []; // an array of TextModels

    public function __construct() {
        $this->corpus = new models\CorpusModel();
        new views\CorpusView($this->corpus);
    }


}

?>

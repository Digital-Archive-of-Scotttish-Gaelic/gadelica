<?php

class CorpusController {

    public $corpus = []; // an array of TextModels

    public function __construct() {
        $this->corpus = new CorpusModel();
        new CorpusView($this->corpus);
    }


}

?>

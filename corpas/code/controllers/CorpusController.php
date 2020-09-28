<?php

class CorpusController {

    private $_corpusModel = null;

    public function __construct() {
        $this->_corpusModel = new CorpusModel();
        new CorpusView($this->_corpusModel);
    }

}

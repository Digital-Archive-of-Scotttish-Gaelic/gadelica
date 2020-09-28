<?php

class CorpusController {

    private $_rootTextModel = null;

    public function __construct() {
        $this->_rootTextModel = new TextModel('https://dasg.ac.uk/corpus/_0');
        new TextView($this->_rootTextModel);
    }

}

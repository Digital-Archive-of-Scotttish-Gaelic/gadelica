<?php

class TextController {

    private $_textModel = null;

    public function __construct($id) {
        $this->_textModel = new TextModel($id);
        new TextView($this->_textModel);
    }

}

<?php

namespace controllers;
use models, views;

class corpus2
{
	private $_model;

  public function __construct() {

    if (isset($_GET["search"])) {
			echo 'WAIT!';
		}
    else {
			if (isset($_GET["text"])) {
				$this->_model = new models\text_sql($_GET["text"]);
				$view = new views\text_sql($this->_model);
				$view->printText();
			}
			else if (isset($_GET["writer"])) {
				if ($_GET["writer"]=='all') {
					$writers = models\writers_sql::getWriters();
					$view = new views\writer_sql();
					$view->listWriters($writers);
				}
				else {
					$this->_model = new models\writer_sql($_GET["writer"]);
					$view = new views\writer_sql();
					$view->printWriter($this->_model);
				}
			}
	    else {
				$this->_model = new models\corpus_sql();
				$textList = $this->_model->getTextList();
				$view = new views\corpus_sql();
				$view->writeTable($textList);
			}
		}

  }

}

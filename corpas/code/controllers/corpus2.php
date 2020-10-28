<?php

namespace controllers;
use models, views;

class corpus2
{

  /*
	// we can come back to this later, once we get the top-level sorted!
	private $_origin;

	public function __construct($origin = null) {
		$this->_origin = $origin;
		$_GET["pp"] = ($_GET["pp"]) ? $_GET["pp"] : 10; // number of results per page
		$_GET["page"] = ($_GET["page"]) ? $_GET["page"] : 1; // results page number
	}
	*/

	//private $_model;

	public function run($action) {

    $id = isset($_GET["id"]) ? $_GET["id"] : "0"; // MMMMM

		switch ($action) {
      case "search":
        echo "WAIT!";
			  break;
      case "browse":
				$model = new models\corpus2($id);
				$view = new views\corpus2($model);
				$view->show();
			  break;

			/*
			// SB CODE:
			case "text":
				$model = new models\text_sql($_GET["id"]);
				$view = new views\text_sql($model);
				$view->printText();
				break;
			case "writer":
				$model = new models\writer_sql($_GET["id"]);
				$view = new views\writer_sql();
				$view->printWriter($model);
				break;
			case "writers":
				$writers = models\writers_sql::getWriters();
				$view = new views\writer_sql();
				$view->listWriters($writers);
				break;
			case "search":
				$searchView = new views\corpussearch(); // gets parameters from URL
				$searchView->writeSearchForm(); // prints HTML for form
				break;
			case "runSearch":
				$searchView = new views\corpussearch();
				$searchModel = new models\corpussearch();
				$searchResults = $searchModel->getDBSearchResults($_GET);
				$resultCount = $searchResults["hits"];
				$searchView->setHits($resultCount);
				$searchView->setOrigin($this->_origin);   //to allow linking back to originating script
				//fetch the results required for this page
				$dbResults = $searchResults["results"];
				//fetch the results from file if corpus view
				$results = ($_GET["view"] == "corpus") ? $searchModel->getFileSearchResults($dbResults) : $dbResults;
				$searchView->writeSearchResults($results, $resultCount);
				break;
			default:  //default to browsing texts
				$model = new models\corpus_sql();
				$textList = $model->getTextList();
				$view = new views\corpus_sql();
				$view->writeTable($textList);
				*/
		}
  }

/*
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
*/


}

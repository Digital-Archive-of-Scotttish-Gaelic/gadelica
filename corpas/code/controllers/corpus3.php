<?php

namespace controllers;
use models, views;

class corpus3
{
	/*
	private $_origin;

	public function __construct($origin = null) {
		$this->_origin = $origin;
		$_GET["pp"] = ($_GET["pp"]) ? $_GET["pp"] : 10; // number of results per page
		$_GET["page"] = ($_GET["page"]) ? $_GET["page"] : 1; // results page number
	}
	*/

	public function run($action) {
		switch ($action) {
			case "text":
				$controller = new text_sql();
				$controller->run($action);
				break;
				/*
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
			*/
			default:  //default to browsing texts
				$controller = new corpus_sql();
				$controller->run($action);
		}
	}
}
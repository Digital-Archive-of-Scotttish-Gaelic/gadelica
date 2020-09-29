<?php

namespace controllers;
use views, models;

class CorpusSearch
{
	private $_origin;   //used to track the initial search page

	public function __construct($origin) {
		$this->_origin = $origin;
		$_GET["pp"] = ($_GET["pp"]) ? $_GET["pp"] : 10; // number of results per page
		$_GET["page"] = ($_GET["page"]) ? $_GET["page"] : 1; // results page number
		if (!isset($_REQUEST["action"])) {
			$_REQUEST["action"] = "newSearch";
		}

		switch ($_REQUEST["action"]) {
			case "newSearch":
				$searchView = new views\CorpusSearch(); // gets parameters from URL
				$searchView->writeSearchForm(); // prints HTML for form
				break;
			case "runSearch":
				$searchView = new views\CorpusSearch();
				$searchModel = new models\CorpusSearch();
				//check if there is an existing result set, if not then run the query
				$searchResults = $searchModel->getDBSearchResults($_GET);
				$resultCount = $searchResults["hits"];
				$searchView->setHits($resultCount);
				$searchView->setOrigin($this->_origin);   //to allow linking back to originating script
				//fetch the results required for this page
				$dbResults = $searchResults["results"];
				//fetch the results from file if
				$results = ($_GET["view"] == "corpus") ? $searchModel->getFileSearchResults($dbResults) : $dbResults;
				$searchView->writeSearchResults($results, $resultCount);
				break;
		}
	}


}

<?php

namespace controllers;
use models, views;

class corpus
{

	public function __construct() {
		$_GET["pp"] = ($_GET["pp"]) ? $_GET["pp"] : 10; // number of results per page
		$_GET["page"] = ($_GET["page"]) ? $_GET["page"] : 1; // results page number
	}

	public function run($action) {

    $id = isset($_GET["id"]) ? $_GET["id"] : "0"; // the root corpus has id = 0

		switch ($action) {
      case "browse":
				$model = new models\corpus_browse($id);
				$view = new views\corpus_browse($model);
				$view->show();
			  break;
			case "search":
				$view = new views\corpus_search(); // gets parameters from URL
				if (empty($_GET["term"])) {   //no search term so print the form
					$view->writeSearchForm(); // prints HTML for form
					break;
				}
				//there is a search term so run the search
				$searchModel = new models\corpus_search();
				$searchResults = $searchModel->getDBSearchResults($_GET); // move to model?
				$resultCount = $searchResults["hits"];
				$view->setHits($resultCount);
				//fetch the results required for this page
				$dbResults = $searchResults["results"];
				//fetch the results from file if corpus view
				$results = ($_GET["view"] == "corpus") ? $searchModel->getFileSearchResults($dbResults) : $dbResults;
				$view->writeSearchResults($results, $resultCount);
				break;
			case "edit":
				$model = new models\corpus_browse($id);
				$view = new views\corpus_browse($model);
				$view->edit();
				break;
			case "save":
				$model = new models\corpus_browse($id);
				$model->save($_POST);
				$model = new models\corpus_browse($id);
				$view = new views\corpus_browse($model);
				$view->show();
				break;
		}
  }

}

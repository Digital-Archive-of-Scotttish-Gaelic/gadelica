<?php

namespace views;
use models;

class search {

	private $_model;   // an instance of models\entries

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		$search = $this->_model->getSearch();
		if (!$search) {
			$search = "activist";
		}
    echo <<<HTML
		<form action="#" method="get" autocomplete="off" id="searchForm"> <!-- Search box -->
			<div class="form-group">
				<div class="input-group">
	        <input id="searchBox" type="text" class="form-control active" name="search" data-toggle="tooltip" title="" autofocus="autofocus" value="{$search}"/>
					<div class="input-group-append">
						<button id="searchButton" class="btn btn-primary" type="submit" data-toggle="tooltip" title="">Siuthad</button>
					</div>
				</div>
			</div>
		</form>
HTML;
    $entries = $this->_model->getEntries();
    if ($entries) {
			echo '<div class="list-group list-group-flush">';
			foreach ($entries as $nextEntry) {
				$url = '?m=entry&mhw=' . $nextEntry[0] . '&mpos=' . $nextEntry[1] . '&msub=' . $nextEntry[2];
	    	echo '<a href="' . $url . '" class="list-group-item list-group-item-action"><strong>';
				echo search::_hi($nextEntry[0],$search) . '</strong> <em>' . models\entries::getShortGd($nextEntry[1]) . '</em>';
				echo ' ' . search::_hi($nextEntry[3],$search) . '</a>';
			}
			echo '</div>';
		}
		else if (isset($_GET["search"])) {
			echo "???";
		}
	}

	private static function _hi($string,$search) {
		if (strpos($string,$search)>-1) {
			return str_replace($search,'<span style="text-decoration:underline;text-decoration-color:red;">'.$search.'</span>',$string);
		}
    else {
      $search = ucfirst($search);
			if (strpos($string,$search)>-1) {
				return str_replace($search,'<span style="text-decoration:underline;text-decoration-color:red;">'.$search.'</span>',$string);
			}
		  else {
			  return $string;
		  }
		}
	}

}

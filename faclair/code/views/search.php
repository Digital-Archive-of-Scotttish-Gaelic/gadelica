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
			$search = "super";
		}
    echo <<<HTML
		<nav class="navbar fixed-top" style="width:100%;">
		  <form class="form-inline" action="#" method="get" autocomplete="off" id="searchForm" style="width:100%;"> <!-- Search box -->
				<div class="input-group" style="width:100%;">
	        <input id="searchBox" type="text" class="form-control active" name="search" data-toggle="tooltip" title="" autofocus="autofocus" value="{$search}"/>
					<div class="input-group-append">
						<button id="searchButton" class="btn btn-primary" type="submit" data-toggle="tooltip" title="">Siuthad</button>
					</div>
				</div>
		  </form>
	  </nav>
HTML;
    if ($this->_model->getSearch()=='') {
	    return;
    }
    echo <<<HTML
		<div class="container-fluid" style="padding-top: 50px;">
HTML;
    $entries_en = $this->_model->getEntriesEn();
		$entries_gd = $this->_model->getEntriesGd();
    if ($entries_en || $entries_gd) {
			if ($entries_en && $entries_gd) {
			echo <<<HTML
			  <small><a data-toggle="collapse" href=".langs">[càch]</a></small>
HTML;
      }
      echo <<<HTML
			  <div class="list-group list-group-flush collapse
HTML;
      if (count($entries_en)>=count($entries_gd)) {
				echo " show ";
			}
      echo <<<HTML
				 langs">
HTML;
      foreach ($entries_en as $nextEntry) {
	      $url = '?m=entry&mhw=' . $nextEntry[0] . '&mpos=' . $nextEntry[1] . '&msub=' . $nextEntry[2];
	      echo '<a href="' . $url . '" class="list-group-item list-group-item-action"><strong>';
	      echo $nextEntry[0] . '</strong> <em>' . models\entry::getPosInfo($nextEntry[1])[0] . '</em>';
	      echo ' ' . search::_hi($nextEntry[3],$search) . '</a>'; // an english term
      }
      echo <<<HTML
			  </div>
				<div class="list-group list-group-flush collapse
HTML;
      if (count($entries_gd)>count($entries_en)) {
				echo " show ";
			}
      echo <<<HTML
				 langs">
HTML;
      foreach ($entries_gd as $nextEntry) {
	      $url = '?m=entry&mhw=' . $nextEntry[0] . '&mpos=' . $nextEntry[1] . '&msub=' . $nextEntry[2];
	      echo '<a href="' . $url . '" class="list-group-item list-group-item-action"><strong>';
	      echo search::_hi($nextEntry[0],$search) . '</strong> <em>' . models\entry::getPosInfo($nextEntry[1])[0] . '</em>';
	      echo ' ' . search::_hi($nextEntry[3],$search) . '</a>'; // an alt hw or form
      }
      echo <<<HTML
	      </div>
	      <div class="list-group list-group-flush collapse langs">
HTML;
		}
    else {
			echo "No results!";
		}
    echo "</div>";
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

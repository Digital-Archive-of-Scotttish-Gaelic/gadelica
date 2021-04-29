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
			$search = "dog";
		}
    echo <<<HTML
      <form action="#" method="get" autocomplete="off" id="searchForm">
        <div class="form-group">
				  <div class="input-group">
					  <input id="searchBox" type="text" class="form-control active" name="search"
						       autofocus="autofocus" value="{$search}"/>
					  <div class="input-group-append">
						  <button id="searchButton" class="btn btn-primary" type="submit">Siuthad</button>
					  </div>
				  </div>
        </div>
        <div class="form-group">
				  <div class="form-check form-check-inline" data-toggle="tooltip" title="Enter English term">
					  <input class="form-check-input" type="radio" name="gd" id="enRadio" value="no"
HTML;
    if (!$this->_model->getGd()) { echo ' checked>'; };
    echo <<<HTML
					  <label class="form-check-label" for="enRadio">Beurla</label>
				  </div>
				  <div class="form-check form-check-inline" data-toggle="tooltip" title="Enter Gaelic term">
					  <input class="form-check-input" type="radio" name="gd" id="gdRadio" value="yes"
HTML;
		if ($this->_model->getGd()) { echo ' checked>'; };
		echo <<<HTML
					  <label class="form-check-label" for="gdRadio">Gàidhlig</label>
				  </div>
        </div>
      </form>
HTML;
    if ($this->_model->getSearch()=='') {
			echo <<<HTML
			  <hr/>
        <p>
          Is e stòr fhaclan agus abairtean na Gàidhlig a tha anns a’ Bhriathradan,
					a tha a’ tiomsachadh bhriathran à iomadh thùs.
					Chaidh an stèidheachadh le sgioba <a href="https://dasg.ac.uk" target="_new">DASG</a>
					ann an Oilthigh Ghlaschu,
					le taic bho <a href="https://www.gaidhlig.scot" target="_new">Bhòrd na Gàidhlig</a>
					agus bho <a href="http://www.soillse.ac.uk" target="_new">Shoillse.
				</p>
HTML;
	    return;
    }
    $entries = $this->_model->getEntries();
		if (!count($entries)) {
			echo '<p>Feuch a-rithist!</p>';
			return;
		}
    echo <<<HTML
			<div class="list-group list-group-flush">
HTML;
    foreach ($entries as $nextEntry) {
	    $url = '?m=entry&mhw=' . $nextEntry[0] . '&mpos=' . $nextEntry[1] . '&msub=' . $nextEntry[2];
	    echo '<a href="' . $url . '" class="list-group-item list-group-item-action"><strong>';
			if ($this->_model->getGd()) {
				echo search::_hi($nextEntry[0],$search);
			}
			else {
        echo $nextEntry[0];
			}
	    echo '</strong> <em>' . models\entry::getPosInfo($nextEntry[1])[0] . '</em>';
	    echo ' <span class="text-muted">' . search::_hi($nextEntry[3],$search) . '</span></a>';
    }
    echo <<<HTML
			</div>
HTML;
	}

	private static function _hi($string,$search) { // highlights all instances of a search term in a string
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

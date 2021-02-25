<?php

namespace views;

class home {

	private $_model;   // an instance of models\home

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
    echo <<<HTML
		<form action="#" method="get" autocomplete="off" id="searchForm"> <!-- Search box -->
			<div class="form-group">
				<div class="input-group">
	        <input id="searchBox" type="text" class="form-control active" name="search" data-toggle="tooltip" title="" autofocus="autofocus" value="bee"/>
					<div class="input-group-append">
						<button id="searchButton" class="btn btn-primary" type="submit" data-toggle="tooltip" title="">Siuthad</button>
					</div>
				</div>
			</div>
		</form>
HTML;
    if ($this->_model->getSearch()!="") {
      echo '<h1>' . $this->_model->getSearch() . '</h1>';
			echo '<div class="list-group list-group-flush">';
			foreach ($this->_model->getEntries() as $nextEntry) {
				$view = new entry_instance($nextEntry);
			  $view->show('embedded');
			}
			echo '</div>';
		}
	}

}

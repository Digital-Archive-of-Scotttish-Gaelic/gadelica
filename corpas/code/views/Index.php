<?php


namespace views;


class Index
{
	public function show() {
		$html = <<<HTML
			<div class="list-group list-group-flush">
			  <a class="list-group-item list-group-item-action" href="?m=search&a=newSearch">search corpus</a>
			  <a class="list-group-item list-group-item-action" href="browseCorpus.php">browse corpus</a>
			  <a class="list-group-item list-group-item-action" href="slipBrowse.php">browse slips</a>
			  <a class="list-group-item list-group-item-action" href="entries.php">browse entries</a>
			  <a class="list-group-item list-group-item-action" href="docs.php">technical documentation</a>
			</div>
HTML;
		echo $html;
	}
}
<?php


namespace views;


class Index
{
	public function show() {
		$html = <<<HTML
			<div class="list-group list-group-flush">
			  <a class="list-group-item list-group-item-action" href="?m=search&a=newSearch">search corpus</a>
			  <a class="list-group-item list-group-item-action" href="?m=corpus&action=browse">browse corpus</a>
			  <a class="list-group-item list-group-item-action" href="?m=slips&a=browse">browse slips</a>
			  <a class="list-group-item list-group-item-action" href="?m=entries&a=browse">browse entries</a>
			  <a class="list-group-item list-group-item-action" href="?m=docs&action=view">technical documentation</a>
			</div>
HTML;
		echo $html;
	}
}
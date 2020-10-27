<?php


namespace views;

class index2
{

	public function __construct() {
		$html = <<<HTML
			<div class="list-group list-group-flush">
				<a class="list-group-item list-group-item-action" href="?m=corpus">browse corpus</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&text=5">view text #5</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&writer=43">view writer #43</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&writer=all">view writers</a>
			  <a class="list-group-item list-group-item-action" href="?m=corpus&search=something">search</a>
				<!--
			  <a class="list-group-item list-group-item-action" href="?m=slips&a=browse">browse slips</a>
			  <a class="list-group-item list-group-item-action" href="?m=entries&a=browse">browse entries</a>
			  <a class="list-group-item list-group-item-action" href="?m=docs&action=view">technical documentation</a>
			-->
			</div>
HTML;
		echo $html;
	}

}

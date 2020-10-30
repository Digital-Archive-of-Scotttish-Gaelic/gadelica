<?php

namespace views;

class home2
{

	public function show() {
		$html = <<<HTML
			<div class="list-group list-group-flush">
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=browse">browse corpus</a>
				<a class="list-group-item list-group-item-action" href="?m=writers&a=browse">browse writers</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=search">search corpus</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=search&id=2">search text #2</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=search&term=craobh">search corpus for 'craobh'</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=search&id=2&term=craobh">search text #2 for 'craobh'</a>
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

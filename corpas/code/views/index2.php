<?php

namespace views;

class index2
{

	public function show() {
		$html = <<<HTML
			<div class="list-group list-group-flush">
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=browse">browse corpus</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=browse&id=2">browse text #2</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=browse&id=2-1">browse text #2-1</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=browse&id=5">browse text #5</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=search">search corpus</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&a=search&id=5">search text #5</a>
				<!--
				// writers are confusing the architecture so gone for now
				<a class="list-group-item list-group-item-action" href="?m=corpus&writer=43">view writer #43</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&writer=all">view writers</a>
			  -->
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

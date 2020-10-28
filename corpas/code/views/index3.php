<?php


namespace views;


class index3
{
	public function show() {
		$html = <<<HTML
			<div class="list-group list-group-flush">
			
				<a class="list-group-item list-group-item-action" href="?m=corpus">browse corpus</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&p=text&id=5">view text #5</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&p=writer&id=43">view writer #43</a>
				<a class="list-group-item list-group-item-action" href="?m=corpus&p=writers">view writers</a>
			  <a class="list-group-item list-group-item-action" href="?m=corpus&p=search">search</a>
			  
			  <!--a class="list-group-item list-group-item-action" href="?m=search&a=newSearch">search corpus</a>
			  <a class="list-group-item list-group-item-action" href="?m=corpus&action=browse">browse corpus</a>
			  <a class="list-group-item list-group-item-action" href="?m=slips&a=browse">browse slips</a>
			  <a class="list-group-item list-group-item-action" href="?m=entries&a=browse">browse entries</a>
			  <a class="list-group-item list-group-item-action" href="?m=docs&action=view">technical documentation</a-->
			</div>
HTML;
		echo $html;
	}
}
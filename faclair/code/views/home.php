<?php


namespace views;

class home {

	public function show() {
    $html = <<<HTML
      <ul>
        <li><a href="?m=entries">entries</a></li>
				<li><a href="?m=sources">sources</a></li>
			</ul>
HTML;
		echo $html;
	}

}

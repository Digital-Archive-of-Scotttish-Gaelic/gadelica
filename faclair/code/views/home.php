<?php


namespace views;

class home {

	public function show() {
    $html = <<<HTML
      <ul>
        <li><a href="?m=entries">faclan</a></li>
				<li><a href="?m=sources">faclairean</a></li>
			</ul>
HTML;
		echo $html;
	}

}

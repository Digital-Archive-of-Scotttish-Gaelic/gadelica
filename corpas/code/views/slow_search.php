<?php


namespace views;


class slow_search
{
	private $_model;  //an instance of models\slow_search

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show($xpath) {
		if ($xpath=="") {
			echo <<<HTML
		    <form>
			    <input type="hidden" name="m" value="corpus"/>
			    <input type="hidden" name="a" value="slow_search"/>
			    <div class="form-group">
				    <div class="input-group">
					    <input type="text" class="form-control" name="xpath" value="//dasg:w[@lemma='craobh']/@id"/>
					    <div class="input-group-append">
						    <button class="btn btn-primary" type="submit">search</button>
					    </div>
				    </div>
			    </div>
		    </form>
HTML;
		} else {
			echo <<<HTML
			<p><a href="?m=corpus&a=slow_search">new slow search</a></p>
			<p>Searching for: {$xpath}</p>
HTML;
			$path = '/var/www/html/dasg.arts.gla.ac.uk/www/gadelica/corpas/xml';
			if (getcwd()=='/Users/stephenbarrett/Sites/gadelica/corpas/code') {
				$path = '../xml';
			}
			else if (getcwd()=='/Users/mark/Sites/gadelica/corpas/code') {
				$path = '../xml';
			}

			$html = <<<HTML
				<table id="table" class="table">					
					<tbody>
HTML;

			$count = 0;
			$it = new \RecursiveDirectoryIterator($path);
			foreach (new \RecursiveIteratorIterator($it) as $nextFile) {
				if ($nextFile->getExtension()=='xml') {
					$filename = "";
					if (getcwd()=='/Users/stephenbarrett/Sites/gadelica/corpas/code') {
						$filename = substr($nextFile,7);
					} else if (getcwd()=='/Users/mark/Sites/gadelica/corpas/code') {
						$filename = substr($nextFile,7);
					} else {
						$filename = substr($nextFile,55);
					}
					$xml = simplexml_load_file($nextFile);
					$xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
					foreach ($xml->xpath($xpath) as $nextWordId) {
						$count++;
            $html .= <<<HTML
							<tr>						
								<th scope="row">{$count}</th>
								<td>{$filename} {$nextWordId}</td>
							</tr>
HTML;
          }
        }
      }
			$html .= <<<HTML
					</tbody>
				</table>
				<div class="pagination"></div>

				<script>
					$(function () {
					  	var items = $("table tr");
							var numItems = items.length;
							var perPage = 10;
							items.slice(perPage).hide();
							if(numItems != 0){
								$(".pagination").pagination({
									items: numItems,
									itemsOnPage: perPage,
									cssStyle: "light-theme",
									onPageClick: function(pageNumber) { 
										var showFrom = perPage * (pageNumber - 1);
										var showTo = showFrom + perPage;
										items.hide().slice(showFrom, showTo).show();
									}
								});
							}
					});
				</script>
HTML;
			echo $html;
    }
	}
}

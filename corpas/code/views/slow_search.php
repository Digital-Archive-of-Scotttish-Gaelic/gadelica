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

			$results = $this->_model->search($xpath);
			$html = <<<HTML
				<table id="table" class="table">					
					<tbody>
HTML;


					foreach ($results as $result) {
						$data = $result["data"];
						$context = $result["context"];
						$count = $result["count"];
            $html .= <<<HTML
							<tr data-filename="{$data["filename"]}" data-id="{$data["id"]}" data-count={$count}>						
								<th scope="row">{$count}</th>
								<td>{$data["date_of_lang"]}</td>
								<td style="text-align: right;">{$context["pre"]["output"]}</td>
								<td>{$context["word"]}</td>
								<td>{$context["post"]["output"]}</td>
							</tr>
HTML;
          }

$finalResult = array_pop($results);


			$html .= <<<HTML
					</tbody>
				</table>
				<div class="loading" style="display:none;"><img src="https://dasg.ac.uk/images/loading.gif" width="200" alt="loading"></div>
				<div class="pagination"></div>

				<a href="#" id="loadMoreResults" title="load more">load more results ...</a>
HTML;
			echo $html;
    }
		$this->_writeResultsJavascript($xpath);
	}

	private function _writeResultsJavascript($xpath) {
		$xpath = urlencode($xpath);
		echo <<<HTML
			<script type="text/javascript" src="js/jquery.simplePagination.js"></script>
			<script>
				$(function() {
				  paginate();
				  
				  $('#loadMoreResults').on('click', document, function () {
				    $('.loading').show();
				    var xpath = '{$xpath}';
				    var filename = $('table tr').last().attr('data-filename');
				    var id = $('table tr').last().attr('data-id');
				    var count = $('table tr').last().attr('data-count');
				    $.getJSON('ajax.php', {action: 'getSlowSearchResults', xpath: xpath, filename: filename, id: id})
				      .done(function (results) {
				        $('.loading').hide();
				        $.each(results, function (key, result) {
				          count++;
				          var data = result.data;
				          var context = result.context;
				          var html = '<tr data-filename="'+data.filename+'" data-id="'+data.id+'" data-count='+count+'>';
				          html += '<th>'+count+'</th>';
				          html += '<td>'+data.date_of_lang+'</td>';
				          html += '<td style="text-align:right;">'+context.pre.output+'</td>';
				          html += '<td>'+context.word+'</td>';
				          html += '<td>'+context.post.output+'</td>';
				          html += '</tr>';
				          $("table").append(html);
				          paginate();
				        });				        
				      });
				  });
				  
				    
				  /** Pagination for results */
				  function paginate() {				    
				    var items = $("table tr");
						var numItems = items.length;
						var perPage = 10;
						items.slice(perPage).hide();
						if(numItems != 0) {
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
							var totalPages = $('.pagination').pagination('getPagesCount');
							$('.pagination').pagination('selectPage', totalPages);
						}
					}
					
				});
			</script>
HTML;
	}
}

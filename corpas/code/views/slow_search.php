<?php


namespace views;


use models;

class slow_search
{
	private $_model;  //an instance of models\slow_search

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show($xpath) {
		if ($xpath=="") {   //no results so write the form
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
			    <div class="form-group">
	          <label class="form-check-label" for="chunkOff">Get all results</label>
	          <input type="radio" id="chunkOff" class="form-control-default" aria-label="Get all results" name="chunk" value="off">
					</div>
					<div class="form-group">
	          <label class="form-check-label" for="chunkOn">Chunk results</label>
	          <input type="radio" id="chunkOn" class="form-control-default" aria-label="Chunk results" name="chunk" value="on" checked>
	        </div>
	        <div class="form-group">
	          <label class="form-check-label" for="chunkValue">Results per chunk</label>
	          <input type="text" id="chunkValue" class="form-control-sm" name="chunkValue" value="10">
					</div>
		    </form>
HTML;
		} else {    //there are results so show them
			models\collection::writeSlipDiv();
			echo <<<HTML
				<p><a href="?m=corpus&a=slow_search">new slow search</a></p>
				<p>Searching for: {$xpath}</p>
HTML;
			$chunkSize = ($_GET["chunk"] == "on") ? $_GET["chunkValue"]-1 : null;
			$results = $this->_model->search($xpath, $chunkSize);
			$html = <<<HTML
				<table id="table" class="table">					
					<tbody>
HTML;

			foreach ($results as $result) {
				$data = $result["data"];
				$context = $data["context"];
				$index = $result["index"];
				$rowNum = $index+1;
				$slipLinkHtml = models\collection::getSlipLinkHtml($result, $index);
				$title = <<<HTML
	        Headword: {$data["lemma"]}<br>
	        POS: {$data["pos"]} ({$data["posLabel"]})<br>
	        Date: {$data["date_of_lang"]}<br>
	        Title: {$data["title"]}<br>
	        Page No: {$data["page"]}<br><br>
	        {$data["filename"]}<br>{$data["id"]}
HTML;
        $html .= <<<HTML
					<tr data-filename="{$data["filename"]}" data-id="{$data["id"]}" data-index={$index}>						
						<th scope="row">{$rowNum}</th>
						<td>{$data["date_of_lang"]}</td>
						<td style="text-align: right;">{$context["pre"]["output"]}</td>
						<td style="text-align: center;"><a target="_blank" href="?m=corpus&a=browse&id={$data["tid"]}&wid={$data["id"]}"
                data-toggle="tooltip" data-html="true" title="{$title}">
              {$context["word"]}
            </a></td>
						<td>{$context["post"]["output"]}</td>
						<td><small>{$slipLinkHtml}</small></td>
					</tr>
HTML;
          }
				$loadMoreResultsHtml = $chunkSize ? '<a href="#" id="loadMoreResults" title="load more">load more results ...</a>' : "";
				$html .= <<<HTML
					</tbody>
				</table>
				<div class="loading" style="display:none;"><img src="https://dasg.ac.uk/images/loading.gif" width="200" alt="loading"></div>
				<div class="pagination"></div>
				{$loadMoreResultsHtml}
HTML;
			echo $html;
    }
		$this->_writeResultsJavascript($xpath, $chunkSize);
	}

	private function _writeResultsJavascript($xpath, $chunkSize) {
		$xpath = urlencode($xpath);
		$chunkSize = $chunkSize ? $chunkSize : 'null';
		echo <<<HTML
			<script type="text/javascript" src="js/jquery.simplePagination.js"></script>
			<script>
				$(function() {
				  if ($('.pagination').length) {
						paginate();
						$('.pagination').pagination('selectPage', 1);    //jump to first page of results on page load
					}
				  
				  $('#chunkOn').on('click', function() {
				    $('#chunkValue').prop('disabled', false);
				  });
				  
				  $('#chunkOff').on('click', function () {
				    $('#chunkValue').prop('disabled', true);  
				  });
				  
				  $('#loadMoreResults').on('click', document, function () {
				    $('.loading').show();
				    var xpath = '{$xpath}';
				    var chunkSize = {$chunkSize};
				    var filename = $('table tr').last().attr('data-filename');
				    var id = $('table tr').last().attr('data-id');
				    var index = $('table tr').last().attr('data-index');
				    $.getJSON('ajax.php', {action: 'getSlowSearchResults', xpath: xpath, chunkSize: chunkSize, 
				          filename: filename, id: id, index: index})
				      .done(function (results) {
				        $('.loading').hide();
				        $.each(results, function (key, result) {
				          index++;
				          var rowNum = index+1;
				          var data = result.data;
				          var context = data.context;
				          var title = 'Headword: '+data.lemma+'<br>';
			            title += 'POS: '+data.pos+' '+ data.posLabel+'<br>';
			            title += 'Date: '+data.date_of_lang+'<br>';
			            title += 'Title: '+data.title+'<br>';
			            title += 'Page No: '+data.page+'<br><br>';
			            title += data.filename+'<br>'+data.id;
				          var html = '<tr data-filename="'+data.filename+'" data-id="'+data.id+'" data-index='+index+'>';
				          html += '<th>'+rowNum+'</th>';
				          html += '<td>'+data.date_of_lang+'</td>';
				          html += '<td style="text-align:right;">'+context.pre.output+'</td>';
				          html += '<td style="text-align: center;">';
				          html += '<a target="_blank" href="?m=corpus&a=browse&id='+data.tid+'&wid='+data.id+'"';
                  html +=  ' data-toggle="tooltip" data-html="true" title="'+title+'">';
                  html += context.word + '</a></td>';
				          html += '<td>'+context.post.output+'</td>';
				          html += '<td><small>'+data.slipLinkHtml+'</small></td>';
				          html += '</tr>';
				          $("table").append(html);
				          paginate();
				        });				        
				      });
				  });
				});   //end of document load handler
				
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
						$('.pagination').pagination('selectPage', totalPages);    //jump to last page of results
					}
				}
			</script>
HTML;
	}
}

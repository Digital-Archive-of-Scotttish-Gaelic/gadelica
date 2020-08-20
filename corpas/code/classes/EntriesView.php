<?php


class EntriesView
{
  public function writeEntry($entry) {
    echo <<<HTML
      <div id="#entryContainer">
        <div>
          <h4>{$entry->getLemma()} ({$entry->getWordclass()})</h4>
        </div>
        <div>
          <h5>Forms:</h5>
          {$this->_getFormsHtml($entry)}
				</div>
				<div>
					<h5>Senses:</h5>
					{$this->_getSensesHtml($entry)}
				</div>
			</div>
HTML;
    $this->_writeJavascript();
  }

  private function _getFormsHtml($entry) {
	  $html = "<ul>";
	  $i=0;
	  foreach ($entry->getForms() as $form) {
		  $i++;
	  	$morphValues = $entry->getSlipMorphValues($form);
		  $morphHtml = "(" . implode(' ', $morphValues) . ")";
	  	$slipData = $entry->getFormSlipData($form);
			$slipList = "<ul>";
			foreach($slipData as $row) {
				$filenameElems = explode('_', $row["filename"]);
				$translation = $this->_formatTranslation($row["translation"]);
				$slipList .= <<<HTML
					<li id="#slip_{$row["auto_id"]}" data-slipid="{$row["auto_id"]}"
						data-toggle="tooltip" 
						title="#{$filenameElems[0]} p.{$row["page"]}: {$row["date_of_lang"]} : {$translation}"
						data-filename="{$row["filename"]}"
						data-id="{$row["id"]}"
						data-precontextscope="{$row["preContextScope"]}"
						data-postcontextscope="{$row["postContextScope"]}"
					></li>
HTML;
			}
	  	$slipList .= "</ul>";
	  	$citationsHtml = <<<HTML
				<a href="#" class="citationsLink" data-type="form" data-index="{$i}">
						citations
				</a>
				<div id="form_citations{$i}" class="citation">
					<div class="spinner">
		        <div class="spinner-border" role="status">
		          <span class="sr-only">Loading...</span>
		        </div>
					</div>
					{$slipList}
				</div>
HTML;

		  $html .= <<<HTML
				<li>{$form} {$morphHtml} {$citationsHtml}</li>
HTML;
	  }
	  $html .= "</ul>";
	  return $html;
  }

	private function _getSensesHtml($entry) {
		$html = "<ul>";
		$i = 0;
		foreach ($entry->getSenses() as $sense) {
			$i++;
			$slipData = $entry->getSenseSlipData($sense);
			$slipList = "<ul>";
			foreach($slipData as $row) {
				$filenameElems = explode('_', $row["filename"]);
				$translation = $this->_formatTranslation($row["translation"]);
				$slipList .= <<<HTML
					<li id="#slip_{$row["auto_id"]}" data-slipid="{$row["auto_id"]}"
						data-toggle="tooltip" 
						title="#{$filenameElems[0]} p.{$row["page"]}: {$row["date_of_lang"]} : {$translation}" 
						data-filename="{$row["filename"]}"
						data-id="{$row["id"]}"
						data-precontextscope="{$row["preContextScope"]}"
						data-postcontextscope="{$row["postContextScope"]}"
					></li>
HTML;
			}
			$slipList .= "</ul>";
			$citationsHtml = <<<HTML
				<a href="#" class="citationsLink" data-type="sense" data-index="{$i}">
						citations
				</a>
				<div id="sense_citations{$i}" class="citation">
					{$slipList}
				</div>
HTML;
			$html .= <<<HTML
				<li>{$sense} {$citationsHtml}</li>
HTML;
		}
		$html .= "</ul>";
		return $html;
	}

	private function _formatTranslation($html) {
  	$text = strip_tags($html);
  	$text = addslashes($text);
  	return $text;
	}

  public function writeBrowseTable($entriesData) {
    $tableBodyHtml = "<tbody>";
    foreach ($entriesData as $entry) {
      $entryUrl = "?action=view&headword={$entry["lemma"]}&wordclass={$entry["wordclass"]}";
      $tableBodyHtml .= <<<HTML
        <tr>
          <td>{$entry["lemma"]}</td>
          <td>{$entry["wordclass"]}</td>
          <td><a href="{$entryUrl}" title="view entry for {$entry["lemma"]}">
            view entry
          </td>
        </tr>
HTML;
    }
    $tableBodyHtml .= "</tbody>";
    echo <<<HTML
        <table id="browseSlipsTable" data-toggle="table" data-pagination="true" data-search="true">
          <thead>
            <tr>
              <th data-sortable="true">Headword</th>
              <th data-sortable="true">Part-of-speech</th>
              <th>Link</th>
            </tr>
          </thead>
          {$tableBodyHtml}
        </table>
HTML;
  }

  private function _writeJavascript() {
  	echo <<<HTML
			<script>
				/**
        *  Load and show the citations for wordforms or senses
        */
				$('.citationsLink').on('click', function () {
			    var citationsLink = $(this);
			    var citationsContainerId = '#' + $(this).attr('data-type') + '_citations' + $(this).attr('data-index');
			    if ($(this).hasClass('hideCitations')) {
			      $(citationsContainerId).hide();
			      $(this).text('citations');
			      $(this).removeClass('hideCitations');
			      return;
			    }
			    $(citationsContainerId + "> ul > li").each(function() {
			      var html = '';
			      var filename = $(this).attr('data-filename');
			      var id = $(this).attr('data-id');
			      var preScope  = $(this).attr('data-precontextscope');
			      var postScope = $(this).attr('data-postcontextscope');
			      var li = $(this);
			      var title = li.prop('title');
			      console.log(li);
			      var url = 'ajax.php?action=getContext&filename='+filename+'&id='+id+'&preScope='+preScope;
			      url += '&postScope='+postScope;
			      $.getJSON(url, function (data) {
			        $('.spinner').show();
			        var preOutput = data.pre["output"];
			        var postOutput = data.post["output"];
			        html = preOutput;
			        if (data.pre["endJoin"] != "right" && data.pre["endJoin"] != "both") {
			          html += ' ';
			        }
			        html += '<span id="slipWordInContext">' + data.word + '</span>';
			        if (data.post["startJoin"] != "left" && data.post["startJoin"] != "both") {
			          html += ' ';
			        }
			        html += postOutput;
			        li.html(html);
			      })
			        .then(function () {
			          $('.spinner').hide();
			        });
			
			    });
			    $(citationsContainerId).show();
			    citationsLink.text('hide');
			    citationsLink.addClass('hideCitations');
			  });
			</script>
HTML;
  }
}
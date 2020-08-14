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
  }

  private function _getFormsHtml($entry) {
	  $html = "<ul>";
	  $i=0;
	  foreach ($entry->getForms() as $form) {
	  	$i++;
	  	$slipData = $entry->getFormSlipData($form);
			$slipList = "<ul>";
			foreach($slipData as $row) {
				$filenameElems = explode('_', $row["filename"]);
				$slipList .= <<<HTML
					<li id="#slip_{$row["auto_id"]}"
						data-toggle="tooltip" title="#{$filenameElems[0]} p.{$row["page"]}: {$row["date_of_lang"]}"
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
					{$slipList}
				</div>
HTML;
		  $html .= <<<HTML
				<li>{$form} {$citationsHtml}</li>
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
				$slipList .= <<<HTML
					<li id="#slip_{$row["auto_id"]}"
						data-toggle="tooltip" title="#{$filenameElems[0]} p.{$row["page"]}: {$row["date_of_lang"]}"
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

  public function writeBrowseTable($entriesData) {
    $tableBodyHtml = "<tbody>";
    foreach ($entriesData as $entry) {
      $entryUrl = "?action=view&headword={$entry["lemma"]}&wordclass={$entry["wordclass"]}";
      $tableBodyHtml .= <<<HTML
        <tr>
          <td>{$entry["lemma"]}</td>
          <td>{$entry["wordclass"]}</td>
          <td><a target="_blank" href="{$entryUrl}" title="view entry for {$entry["lemma"]}">
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
}
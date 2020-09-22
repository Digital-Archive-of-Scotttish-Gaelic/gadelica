<?php


class EntriesView
{
  public function writeEntry($entry) {
  	$lemma = $entry->getLemma();
  	$wordclass = $entry->getWordclass();
  	$abbr = Functions::getWordclassAbbrev($wordclass);
    echo <<<HTML
      <div id="#entryContainer">
        <div>
          <h4>{$lemma} <em>{$abbr}</em></h4>
          <input type="hidden" id="lemma" value="{$lemma}">
          <input type="hidden" id="wordclass" value="{$wordclass}">
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
    Slips::writeSlipDiv();
    $this->_writeSenseModal();
    $this->_writeJavascript();
  }

  private function _getFormsHtml($entry) {
	  $html = "<ul>";
	  $i=0;
	  foreach ($entry->getUniqueForms() as $slipId => $formString) {
		  $i++;
			$formElems = explode('|', $formString);
			$form = $formElems[0];
			$morph = $formElems[1];
			$hideText = array("unmarked person", "unmarked number");
			$morph = str_replace($hideText, "", $morph);
		  $morphHtml = "(" . $morph . ")";
		  $slipData = array();
	  	$formSlipIds = $entry->getFormSlipIds($slipId);
			foreach ($formSlipIds as $id) {
				$slipData[] = Slips::getSlipInfoBySlipId($id);
			}
			$slipList = '<table class="table"><tbody>';
			foreach($slipData as $data) {
				foreach ($data as $row) {
					$translation = $this->_formatTranslation($row["translation"]);
					$slipLinkData = array(
						"auto_id" => $row["auto_id"],
						"lemma" => $row["lemma"],
						"pos" => $row["pos"],
						"id" => $row["id"],
						"filename" => $row["filename"],
						"uri" => "",
						"date_of_lang" => $row["date_of_lang"],
						"title" => $row["title"],
						"page" => $row["page"]
					);
					$filenameElems = explode('_', $row["filename"]);
					$slipList .= <<<HTML
					<tr id="#slip_{$row["auto_id"]}" data-slipid="{$row["auto_id"]}"
							data-filename="{$row["filename"]}"
							data-id="{$row["id"]}"
							data-precontextscope="{$row["preContextScope"]}"
							data-postcontextscope="{$row["postContextScope"]}"
							data-date="{$row["date_of_lang"]}">
						<!--td data-toggle="tooltip"
							title="#{$filenameElems[0]} p.{$row["page"]}: {$row["date_of_lang"]} : {$translation}"
							class="entryCitationContext"></td-->
						<td class="entryCitationContext"></td>
						<td class="entryCitationSlipLink">{$this->_getSlipLink($slipLinkData)}</td>
						<td><a target="_blank" href="#" class="entryCitationTextLink"><small>view in text</small></td>
					</tr>
HTML;
				}
			}
	  	$slipList .= "</tbody></table>";
	  	$citationsHtml = <<<HTML
				<small><a href="#" class="citationsLink" data-type="form" data-index="{$i}">
						citations
				</a></small>
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
  	$orphanedSensesHtml = $this->_getOrphanSensesHtml($entry);
  	if ($orphanedSensesHtml != "") {
		  $html = "<ul>" . $orphanedSensesHtml . "</ul>";
	  }
  	$html .= <<<HTML
			<div id="groupedSenses">
				<h6>Grouped Senses <a id="showIndividual" href="#" title="show individual senses"><small>show individual</small></a></h6> 
				<ul>
HTML;
		$html .= $this->_getGroupedSensesHtml($entry);
		$html .= '</ul></div>';

		$html .= <<<HTML
			<div id="individualSenses" class="hide">
				<h6>Indivdual Senses <a id="showGrouped" href="#" title="show grouped senses"><small>show grouped</small></a></h6> 
				<ul>
HTML;
		$html .= $this->_getIndividualSensesHtml($entry);
		$html .= '</ul></div>';
		return $html;
	}

	private function _getOrphanSensesHtml($entry) {
		/* Get any citations without senses */
		$html = "";
		$nonSenseSlipIds = SenseCategories::getNonCategorisedSlipIds($entry->getLemma(), $entry->getWordclass());
		if (count($nonSenseSlipIds)) {
			$slipData = array();
			$index = 0;
			foreach ($nonSenseSlipIds as $slipId) {
				$index++;
				$slipData[] = Slips::getSlipInfoBySlipId($slipId);
			}
			$html .= $this->_getSlipListHtml($slipData, "uncategorised", "orp_" . $index);
		}
		return $html;
	}

	private function _getIndividualSensesHtml($entry) {
		/* Get citations for individual senses */
		$individualSenses = $entry->getIndividualSenses();
		$index = 0;
		foreach ($individualSenses as $sense => $slipIds) {
			$slipData = array();
			foreach ($slipIds as $slipId) {
				$index++;
				$slipData[] = Slips::getSlipInfoBySlipId($slipId);
			}
			$html .= $this->_getSlipListHtml($slipData, $sense, "ind_".$index);
		}
		return $html;
	}

	private function _getGroupedSensesHtml($entry) {
  	/* Get the citations with grouped senses */
		$index = 0;
		foreach ($entry->getUniqueSenses() as $slipId => $sense) {
			$slipData = array();
			$senseSlipIds = $entry->getSenseSlipIds($slipId);
			foreach ($senseSlipIds as $id) {
				$index++;
				$slipData[] = Slips::getSlipInfoBySlipId($id);
			}
			$html .= $this->_getSlipListHtml($slipData, $sense, "grp_".$index);
		}
		return $html;
	}


	private function _getSlipListHtml($slipData, $sense, $index) {
		$slipList = '<table class="table"><tbody>';
		foreach($slipData as $data) {
			foreach ($data as $row) {
				$filenameElems = explode('_', $row["filename"]);
				$translation = $this->_formatTranslation($row["translation"]);
				$slipLinkData = array(
					"auto_id" => $row["auto_id"],
					"lemma" => $row["lemma"],
					"pos" => $row["pos"],
					"id" => $row["id"],
					"filename" => $row["filename"],
					"uri" => "",
					"date_of_lang" => $row["date_of_lang"],
					"title" => $row["title"],
					"page" => $row["page"]
				);
				$slipList .= <<<HTML
					<tr id="#slip_{$row["auto_id"]}" data-slipid="{$row["auto_id"]}"
							data-filename="{$row["filename"]}"
							data-id="{$row["id"]}"
							data-precontextscope="{$row["preContextScope"]}"
							data-postcontextscope="{$row["postContextScope"]}"
							data-date="{$row["date_of_lang"]}">
						<!--td data-toggle="tooltip"
							title="#{$filenameElems[0]} p.{$row["page"]}: {$row["date_of_lang"]} : {$translation}"
							class="entryCitationContext"></td-->
						<td  class="entryCitationContext"></td>
						<td class="entryCitationSlipLink">{$this->_getSlipLink($slipLinkData)}</td>
						<td><a target="_blank" href="#" class="entryCitationTextLink"><small>view in text</small></td>
					</tr>
HTML;
			}
		}
		$slipList .= "</tbody></table>";
		$citationsHtml = <<<HTML
				<small><a href="#" class="citationsLink" data-type="sense" data-index="{$index}">
						citations
				</a></small>
				<div id="sense_citations{$index}" class="citation">
					{$slipList}
				</div>
HTML;
		$senses = explode('|', $sense);
		$senseString = "";
		foreach ($senses as $s) {
			$badge = ($s == "uncategorised") ? "badge-secondary" : "badge-success";
			$senseString .= <<<HTML
					<span data-toggle="modal" data-target="#senseModal" title="rename this sense" class="badge {$badge} entrySense">{$s}</span> 
HTML;

		}
		$html = <<<HTML
				<li>{$senseString} {$citationsHtml}</li>
HTML;
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

  private function _writeSenseModal() {
  	echo <<<HTML
			<div class="modal fade" id="senseModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Rename Sense</h5>
              </div>
              <div class="modal-body">
                <h5><span id="oldSenseName"></span></h5>
                <label for="newSenseName">New Sense Name:</label>
                <input type="text" id="newSenseName">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                <button type="button" id="editSense" class="btn btn-primary">save</button>
              </div>
            </div>
          </div>
        </div>
HTML;

  }

  private function _getSlipLink($result) {
		return <<<HTML
						<small>
                <a href="#" class="slipLink2"
                    data-toggle="modal" data-target="#slipModal"
                    data-auto_id="{$result["auto_id"]}"
                    data-headword="{$result["lemma"]}"
                    data-pos="{$result["pos"]}"
                    data-id="{$result["id"]}"
                    data-xml="{$result["filename"]}"
                    data-uri="{$result["uri"]}"
                    data-date="{$result["date_of_lang"]}"
                    data-title="{$result["title"]}"
                    data-page="{$result["page"]}"
                    data-resultindex="">
                      view slip
                </a>
            </small>
HTML;
  }

  private function _writeJavascript() {
  	echo <<<HTML
			<script>
				$('#showIndividual').on('click', function () {
				  $('#groupedSenses').hide();
				  $('#individualSenses').show();
				  return false;
				});
				
				$('#showGrouped').on('click', function () {
				  $('#individualSenses').hide();
				  $('#groupedSenses').show();
				  return false;
				});
				
				$('.entrySense').on('click', function() {
				  var oldName = $(this).text();
				  $('#oldSenseName').text(oldName);
				});
				
				$('#editSense').on('click', function () {
				  var oldName = $('#oldSenseName').text();
				  var newName = $('#newSenseName').val();
				  var lemma = $('#lemma').val();
				  var wordclass = $('#wordclass').val();
					var url = 'ajax.php?action=renameSense&lemma=' + lemma + '&wordclass=' + wordclass;
					url += ' &oldName=' + oldName + '&newName=' + newName;
					$('.entrySense').each(function(index) {
					  if ($(this).text() == oldName) {
					    $(this).text(newName);
					  }
					});
					$('#senseModal').modal('hide');
					$.ajax({url: url});
				});
								
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
			    $(citationsContainerId + "> table > tbody > tr").each(function() {
			      var date = $(this).attr('data-date');
			      var html = '<span class="text-muted">' + date + '.</span> ';
			      var filename = $(this).attr('data-filename');
			      var id = $(this).attr('data-id');
			      var preScope  = $(this).attr('data-precontextscope');
			      var postScope = $(this).attr('data-postcontextscope');
			      var tr = $(this);
			      var title = tr.prop('title');
			      var url = 'ajax.php?action=getContext&filename='+filename+'&id='+id+'&preScope='+preScope;
			      url += '&postScope='+postScope;
			      $.getJSON(url, function (data) {
			        $('.spinner').show();
			        var preOutput = data.pre["output"];
			        var postOutput = data.post["output"];
			        var url = 'viewText.php?uri=' + data.uri + '&id=' + id; //with the wordId
			        tr.find('.entryCitationTextLink').attr('href', url); //add the link to text url
			        html += preOutput;
			        if (data.pre["endJoin"] != "right" && data.pre["endJoin"] != "both") {
			          html += ' ';
			        }
			        //html += '<span id="slipWordInContext">' + data.word + '</span>';
              html += '<mark>' + data.word + '</mark>'; // MM
			        if (data.post["startJoin"] != "left" && data.post["startJoin"] != "both") {
			          html += ' ';
			        }
			        html += postOutput;
			        tr.find('.entryCitationContext').html(html);
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

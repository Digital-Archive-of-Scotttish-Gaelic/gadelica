<?php


class SlipBrowseView
{
  public function writeBrowseTable() {
    //$tableBodyHtml = "<tbody>";
    /*$slipInfo = Slips::getAllSlipInfo();
    foreach ($slipInfo as $slip) {
      $categoriesHtml = $morphHtml = "";
      if ($slip["category"]) {
      	$slip["category"] = array_unique($slip["category"]);
        foreach ($slip["category"] as $category) {
          $categoriesHtml .= <<<HTML
            <span class="badge badge-success">{$category}</span>
HTML;
        }
        if ($slip["relation"]) {
	        $slip["relation"] = array_unique($slip["relation"]);
        	foreach ($slip["relation"] as $value) {
		        $morphHtml .= <<<HTML
              <span class="badge badge-secondary">{$value}</span>
HTML;
	        }
        }
      }
      $slipUrl = <<<HTML
                <a href="#" class="slipLink2"
                    data-toggle="modal" data-target="#slipModal"
                    data-auto_id="{$slip["auto_id"]}"
                    data-headword="{$slip["lemma"]}"
                    data-pos="{$slip["pos"]}"
                    data-id="{$slip["id"]}"
                    data-xml="{$slip["filename"]}"
                    data-uri="{$slip["uri"]}"
                    data-date="{$slip["date_of_lang"]}"
                    data-title="{$slip["title"]}"
                    data-page="{$slip["page"]}"
                    data-resultindex="-1"
                    title="view slip {$slip["auto_id"]}">
                    {$slip["auto_id"]}
                </a>
HTML;
      $tableBodyHtml .= <<<HTML
        <tr>
            <td>{$slipUrl}</td>
            <td>{$slip["lemma"]}</td>
            <td>{$slip["wordform"]}</td>
            <td>{$slip["wordclass"]}</td>
            <td>{$categoriesHtml}</td>
            <td>{$morphHtml}</td>
            <td>{$slip["firstname"]} {$slip["lastname"]}</td>
            <td>{$slip["lastUpdated"]}</td>
            <td><input type="checkbox" name="printSlips[{$slip["auto_id"]}]"></td>
        </tr>
HTML;
    }*/
    //$tableBodyHtml .= "</tbody>";
    echo <<<HTML
      <table id="table" data-toggle="table" data-ajax="ajaxRequest"
        data-search="true"
        data-side-pagination="server"
        data-pagination="true">
          <thead>
              <tr>
                  <th data-field="auto_id" data-sortable="true">ID</th>
                  <th data-field="lemma" data-sortable="true">Headword</th>
                  <th data-field="wordform" data-sortable="true">Wordform</th>
                  <th data-field="wordclass" data-sortable="true">Part-of-speech</th>
                  <th data-field="categories">Categories</th>
                  <th data-field="senses">Senses</th>
                  <th data-field="fullname" data-sortable="true">Updated By</th>
                  <th data-field="lastUpdated" data-sortable="true">Date</th>
                  <th data-field="printSlip">Print</th>
              </tr>
          </thead>
      </table>
      <a href="printSlip.php?action=print" target="_blank" id="printSlips" class="btn btn-primary disabled">print</a>
HTML;

    Slips::writeSlipDiv();
    $this->_writeJavascript();
  }

  private function _writeJavascript() {
    echo <<<HTML
			<script>
			
				$(function () {
					$(document). on('change', '.chooseSlip', function () {
					  var elemId = $(this).attr('id');
					  var elemParts = elemId.split('_');
					  var slipId = elemParts[1];
					  var url = 'ajax.php?action=updatePrintList';
						if ($(this).prop('checked')) {  //add to the print list
						  url += '&addSlip=' + slipId;
						} else {    //remove from the print list
							url += '&removeSlip=' + slipId; 
						}
						var count = null;
						$.getJSON(url, function (data) {
						  count = data.count;
						}).done(function () {
						    if (count) {
						      $('#printSlips').removeClass('disabled');
						    } else {
						      $('#printSlips').addClass('disabled');
						    }
						});
					});
				});
				
				/**
				* Clear the checkboxes when the print button is clicked
				*/
				$('#printSlips').on('click', function () {
				  $(this).addClass('disabled');
				  $('.chooseSlip').prop('checked', '');
				});
				
				/**
				* Runs an AJAX request to populate the Bootstrap table
				* @param params
				*/
			  function ajaxRequest(params) {
			    $.getJSON( 'ajax.php?action=getSlips&' + $.param(params.data), {format: 'json'}).then(function (res) {
			      params.success(res)
					});
			  }
</script>
HTML;
  }
}
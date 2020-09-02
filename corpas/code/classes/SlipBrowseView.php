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
			<form target="_blank" action="printSlip.php">
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
                    <th>Categories</th>
                    <th>Morphological</th>
                    <th data-field="fullname" data-sortable="true">Updated By</th>
                    <th data-field="lastUpdated" data-sortable="true">Date</th>
                    <th data-checkbox="true">Print</th>
                </tr>
            </thead>
        </table>
        <input type="hidden" name="action" value="print">
        <button type="submit" class="btn btn-primary">print</button>
      </form>

<script>
  function ajaxRequest(params) {
    
    $.getJSON( 'ajax.php?action=getSlips&' + $.param(params.data), {format: 'json'}).then(function (res) {
      console.log(typeof res);
      params.success(res)
		});
     
    /*var url = 'ajax.php'
    $.get(url + '?action=getSlips&' + $.param(params.data)).then(function (res) {
      console.log(typeof res);
      params.success(res)
    });*/
  }
</script>

HTML;
    Slips::writeSlipDiv();
  }
}
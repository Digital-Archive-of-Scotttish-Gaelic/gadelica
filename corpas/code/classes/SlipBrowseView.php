<?php


class SlipBrowseView
{
  public function writeBrowseTable() {
    $tableBodyHtml = "<tbody>";
    $slipInfo = Slips::getAllSlipInfo();
    foreach ($slipInfo as $slip) {
      $categoriesHtml = "";
      if ($slip["category"]) {
        foreach ($slip["category"] as $category) {
          $categoriesHtml .= <<<HTML
            <span class="badge badge-success">{$category}</span>
HTML;
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
            <td>{$slip["firstname"]} {$slip["lastname"]}</td>
            <td>{$slip["lastUpdated"]}</td>
        </tr>
HTML;
    }
    $tableBodyHtml .= "</tbody>";
    echo <<<HTML
        <table id="browseSlipsTable" data-toggle="table" data-pagination="true" data-search="true">
            <thead>
                <tr>
                    <th data-sortable="true">ID</th>
                    <th data-sortable="true">Headword</th>
                    <th data-sortable="true">Wordform</th>
                    <th data-sortable="true">Part-of-speech</th>
                    <th>Categories</th>
                    <th data-sortable="true">Updated By</th>
                    <th data-sortable="true">Date</th>
                </tr>
            </thead>
            {$tableBodyHtml}
        </table>
HTML;
    Slips::writeSlipDiv();
  }
}
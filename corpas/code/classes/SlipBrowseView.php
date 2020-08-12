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
      $tableBodyHtml .= <<<HTML
        <tr>
            <td>{$slip["auto_id"]}</td>
            <td>{$slip["lemma"]}</td>
            <td>{$slip["wordform"]}</td>
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
                    <th>Categories</th>
                    <th data-sortable="true">Updated By</th>
                    <th data-sortable="true">Date</th>
                </tr>
            </thead>
            {$tableBodyHtml}
        </table>
HTML;
  }
}
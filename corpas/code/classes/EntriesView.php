<?php


class EntriesView
{
  public function writeEntry($entry) {
    echo <<<HTML
        {$entry["headword"]} - {$entry["wordclass"]}
HTML;
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
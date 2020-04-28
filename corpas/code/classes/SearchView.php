<?php


class SearchView
{
  public function writeSearchForm() {
    echo <<<HTML
      <form>
        <input type="text" name="search"/>
        <input type="hidden" name="action" value="runSearch"/>
        <button name="submit" type="submit">go</button>
      </form>
HTML;
  }

  public function writeSearchResults($results) {
    echo '<a href="search.php?action=newSearch" title="new search">< new search</a>';
    $rowNum = 1;
    echo <<<HTML
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Wordform</th>
                    <th scope="col">Filename</th>
                    <th scope="col">id</th>
                </tr>
            </thead>
            <tbody>             
HTML;
    foreach ($results as $result) {
      echo <<<HTML
                <tr>
                    <th scope="row">{$rowNum}</th>
                    <td>{$result["wordform"]}</td>
                    <td>{$result["filename"]}</td>
                    <td>{$result["id"]}</td>
                </tr>
HTML;
      $rowNum++;
    }
    echo <<<HTML
            </tbody>
        </table>
HTML;
  }
}
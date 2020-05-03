<?php


class SearchView
{
  private $_page = 1;
  private $_resultCount = 0;
  private $_perpage;
  private $_search;

  public function __construct() {
    $this->_search      = isset($_GET["search"]) ? $_GET["search"] : null;
    $this->_perpage     = isset($_GET["pp"]) ? $_GET["pp"] : 10;
    $this->_page         = isset($_GET["page"]) ? $_GET["page"] : 1;
  }

  public function writeSearchForm() {
    echo <<<HTML
      <form>
        <input type="text" name="search"/>
        <input type="hidden" name="action" value="runSearch"/>
        <button name="submit" type="submit">go</button>
      </form>
HTML;
  }

  public function writeSearchResults($results, $resultTotal) {
    //echo '<a href="search.php?action=newSearch" title="new search">< new search</a>';
    $rowNum = $this->_page * $this->_perpage - $this->_perpage + 1;
    echo <<<HTML
        <table class="table">
            <tbody>
HTML;
    foreach ($results as $result) {
      echo <<<HTML
                <tr>
                    <th scope="row">{$rowNum}</th>
HTML;
      $this->_writeSearchResult($result);
      echo <<<HTML
                </tr>
HTML;
      $rowNum++;
    }
    echo <<<HTML
            </tbody>
        </table>

        <ul id="pagination" class="pagination-sm"></ul>
HTML;
    $this->_writeJavascript($resultTotal);
  }

  /* print out search result as table row */
  private function _writeSearchResult($result) {
    echo '<td style="float: right;">';
    $filename = trim($result['filename']);
    $id = trim($result['id']);
    $xml = simplexml_load_file(INPUT_FILEPATH . trim($result['filename']));
    $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    $xpath = '/dasg:text/@ref';
    $out = $xml->xpath($xpath);
    $uri = $out[0];
    $xpath = "//dasg:w[@id='{$id}']/preceding::*";
    $words = $xml->xpath($xpath);
    echo implode(' ', array_slice($words,-12));
    echo '</td>';
    echo '<td style="float: center;"><a href="viewText.php?uri=' . $uri . '&id=' . $id . '" title="' . $filename . ' ' . $id . '">';
    $xpath = "//dasg:w[@id='{$id}']";
    $word = $xml->xpath($xpath);
    echo $word[0];
    echo '</a></td>';
    echo '<td>';
    $xpath = "//dasg:w[@id='{$id}']/following::*";
    $words = $xml->xpath($xpath);
    echo implode(' ', array_slice($words,0,12));
    echo '</td><td><small><a href="#" data-uri="' . $uri . '" data-id="' . $id . '" data-xml="' . $filename . '">slip</a></small></td>';
  }

  /**
   * Writes the Javascript required for the pagination
   */
  private function _writeJavascript($resultTotal) {
    echo <<<HTML
            <script>
                $(function() {
			     /*
				    Pagination handler
			     */
		          $("#pagination").pagination({
				          currentPage: {$this->_page},
		              items: {$resultTotal},
		              itemsOnPage: {$this->_perpage},
		              cssStyle: "light-theme",
		              onPageClick: function(pageNum) {
					           window.location.assign('search.php?action=runSearch&pp={$this->_perpage}&page=' + pageNum + '&search={$this->_search}');
		              }
		          });
		      });
	       </script>
HTML;
  }
}

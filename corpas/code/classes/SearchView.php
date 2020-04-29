<?php


class SearchView
{
  private $_page = 1;
  private $_resultCount = 0;
  private $_perpage = 10;
  private $_search = "";

  public function __construct() {
    $this->_search     = isset($_GET["search"]) ? $_GET["search"] : null;
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

  public function writeSearchResults($results) {
    echo '<a href="search.php?action=newSearch" title="new search">< new search</a>';
    $rowNum = 1;
    echo <<<HTML
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Filename</th>
                    <th scope="col">id</th>
                    <th scope="col">form</th>
                </tr>
            </thead>
            <tbody>
HTML;
    foreach ($results as $result) {
      echo <<<HTML
                <tr>
                    <th scope="row">{$rowNum}</th>
                    <td>{$result["filename"]}</td>
                    <td>{$result["id"]}</td>
HTML;
      $this->_writeSearchResult($result); // MM added this
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
    $this->_writeJavascript();
  }

  /* MM: added following to encapsulate this bit of code */
  private function _writeSearchResult($result) {
    echo '<td>';
    $filename = trim($result['filename']);
    $id = trim($result['id']);
    $xml = simplexml_load_file(INPUT_FILEPATH . trim($result['filename']));
    $xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
    $xpath = <<<XPATH
      //dasg:w[@id='{$id}']
XPATH;
    $word = $xml->xpath($xpath);
    echo $word[0];
    echo '</td>';
  }

  /**
   * Writes the Javascript required for the pagination
   */
  private function _writeJavascript() {
    echo <<<HTML
            <script>
                $(function() {
			     /*
				    Pagination handler
			     */
		          $("#pagination").pagination({
				          currentPage: {$this->_page},
		              items: {$this->_resultCount},
		              itemsOnPage: {$this->_perpage},
		              cssStyle: "light-theme",
		              onPageClick: function(pageNum) {
					   $('#concResultsTable').hide();
					   $('#concResultsLoading').show();
					   window.location.assign('search.php?pp={$this->_perpage}&page=' + pageNum + '&search={$this->_search}');
		              }
		          });
		      });
	       </script>
HTML;
  }
}

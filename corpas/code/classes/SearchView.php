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

        <ul id="pagination" class="pagination-sm"></ul>
HTML;
    $this->_writeJavascript();
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
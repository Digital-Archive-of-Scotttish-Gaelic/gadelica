<?php


class SearchView
{
  private $_page = 1;
  private $_resultCount = 0;
  private $_perpage;
  private $_search;
  private $_mode, $_case, $_accent, $_lenition;

  public function __construct() {
    $this->_search      = isset($_GET["search"]) ? $_GET["search"] : null;
    $this->_perpage     = isset($_GET["pp"]) ? $_GET["pp"] : 10;
    $this->_page        = isset($_GET["page"]) ? $_GET["page"] : 1;
    $this->_mode        = $_GET["mode"] == "wordform" ? "wordform" : "headword";
    $this->_case        = $_GET["case"];
    $this->_accent     = $_GET["accent"];
    $this->_lenition    = $_GET["lenition"];
  }

  public function writeSearchForm() {
    echo <<<HTML
      <form>
        <input type="text" name="search"/>
        <input type="hidden" name="action" value="runSearch"/>
        <div class="radio">
            <label class="radio-inline"><input type="radio" name="mode" id="headwordRadio" value="headword"checked>headword</label>
            &nbsp;
            <label class="radio-inline"><input type="radio" name="mode" id="wordformRadio" value="wordform">wordform</label>
        </div>
        
        <div id="wordformOptions">
          <div class="form-check form-check-inline">
              <input class="form-check-input" type="checkbox" id="caseSensitiveRadio" name="case" value="sensitive">
              <label class="form-check-label" for="caseSensitiveRadio">case sensitive</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="accentSensitiveRadio" name="accent" value="sensitive">
            <label class="form-check-label" for="accentSensitiveRadio">accent sensitive</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="lenitionSensitiveRadio" name="lenition" value="sensitive">
            <label class="form-check-label" for="lenitionSensitiveRadio">lenition sensitive</label>
          </div>
        </div>

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
    if (count($results)) {
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
    } else {
      echo <<<HTML
                <tr><th>Sorry, there were No results for <em>{$this->_search}</em></th></tr>
HTML;

    }
    $this->_writeInfoDiv();
    $this->_writeJavascript($resultTotal);
  }

  /* print out search result as table row */
  private function _writeSearchResult($result) {
    echo '<td style="text-align: right;">';
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
    echo '<td style="text-align: center;"><a href="viewText.php?uri=' . $uri . '&id=' . $id . '" title="' . $filename . ' ' . $id . '">';
    $xpath = "//dasg:w[@id='{$id}']";
    $word = $xml->xpath($xpath);
    echo $word[0];
    echo '</a></td>';
    echo '<td>';
    $xpath = "//dasg:w[@id='{$id}']/following::*";
    $words = $xml->xpath($xpath);
    echo implode(' ', array_slice($words,0,12));
    echo '</td><td><small><a href="#" class="slip" data-uri="' . $uri . '" data-id="' . $id . '" data-xml="' . $filename . '">slip</a></small></td>';
  }

  private function _writeInfoDiv() {
    echo <<<HTML
        <div id="info"><h1>Hello world</h1></hq></div>
HTML;
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
				            var url = 'search.php?action=runSearch&mode={$this->_mode}&pp={$this->_perpage}&page=' + pageNum + '&search={$this->_search}';
				            url += '&case={$this->_case}&accent=($this->_accent}&lenition={$this->_lenition}';
					           window.location.assign(url);
		              }
		          });
		      });
	       </script>
HTML;
  }
}

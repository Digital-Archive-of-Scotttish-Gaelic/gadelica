<?php


class SearchView
{
  private $_page = 1;
  private $_hits = 0;
  private $_perpage;
  private $_search;
  private $_mode, $_case, $_accent, $_lenition, $_view;
  private $_xmlFile;

  public function __construct() {
    $this->_search      = isset($_GET["search"]) ? $_GET["search"] : null;
    $this->_perpage     = isset($_GET["pp"]) ? $_GET["pp"] : 10;
    $this->_page        = isset($_GET["page"]) ? $_GET["page"] : 1;
    $this->_mode        = $_GET["mode"] == "wordform" ? "wordform" : "headword";
    $this->_case        = $_GET["case"];
    $this->_accent      = $_GET["accent"];
    $this->_lenition    = $_GET["lenition"];
    $this->_view        = (isset($_GET["view"])) ? $_GET["view"] : "corpus";
  }

  public function writeSearchForm() {
    echo <<<HTML
      <form>
        <input type="text" name="search"/>
        <input type="hidden" name="action" value="runSearch"/>
        <div class="radio">
            <label class="radio-inline"><input type="radio" name="mode" id="headwordRadio" value="headword"checked>headword</label>     &nbsp;
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

        <div class="radio" id="wordformView">
          <label class="radio-inline"><input type="radio" name="view" id="corpusViewRadio" value="corpus" checked>corpus view</label>&nbsp;
          <label class="radio-inline"><input type="radio" name="view" id="dictionaryViewRadio" value="dictionary">dictionary view</label>
        </div>

        <button name="submit" type="submit">go</button>
      </form>
HTML;
  }

  public function writeSearchResults($results, $resultTotal) {
    if ($this->_view == "dictionary") {
      $this->_writeDictionaryView($results);
      return;
    }
    $rowNum = $this->_page * $this->_perpage - $this->_perpage + 1;
    echo <<<HTML
        <table class="table">
            <tbody>
HTML;
    if (count($results)) {
      $filename = "";
      foreach ($results as $result) {
        if ($filename != $result["filename"]) {
          $filename = $result["filename"];
          $this->_xmlFile = new XmlFileHandler($filename);
        }
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
    $context = $this->_xmlFile->getContext($result["id"], 12);
    echo <<<HTML
        <td>{$result["lemma"]}</td>
        <td>{$result["pos"]}</td>
        <td style="text-align: right;">{$context["pre"]}</td>
        <td style="text-align: center;">
            <a href="viewText.php?uri={$context["uri"]}&id={$result["id"]}"
                    title="{$this->_xmlFile->getFilename()}{$result["id"]}">
                {$context["word"]}
            </a>
        </td>
        <td>{$context["post"]}</td>
        <td>
            <small><a href="#" class="slip" data-uri="{$context["uri"]}"
                data-id="{$result["id"]}" data-xml="{$this->_xmlFile->getFilename()}">slip</a>
            </small>
        </td>
HTML;
    return;
  }

  private function _writeDictionaryView($results) { // added by MM
    echo '<h3>' . $results[0]['lemma'] . '</h3>';
    $forms = [];
    foreach ($results as $nextResult) {
      $forms[] = $nextResult['wordform'] . '|' . $nextResult['pos'];
    }
    $forms = array_unique($forms);
    echo <<<HTML
      <table class="table">
        <tbody>
HTML;
    foreach ($forms as $nextForm) {
      $array = explode('|',$nextForm);
      echo '<tr><td>' . $array[0] . '</td><td>' . $array[1] . '</td><td>';
      foreach ($results as $nextResult) {
        if ($nextResult['wordform']==$array[0] && $nextResult['pos']==$array[1]) {
          echo $nextResult['filename'] . ' ' . $nextResult['id'] . '<br/>';
        }
      }
      echo '</td></tr>';
    }
    echo <<<HTML
        </tbody>
      </table>
HTML;
    return;
  }

  private function _writeInfoDiv() {
    echo <<<HTML
        <div id="info"><h1>Hello world</h1></div>
HTML;
  }

  public function setHits($num) {
    $this->_hits = $num;
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
				            url += '&case={$this->_case}&accent={$this->_accent}&lenition={$this->_lenition}';
				            url += '&hits={$this->_hits}&view={$this->_view}';
					           window.location.assign(url);
		              }
		          });
		      });
	       </script>
HTML;
  }
}

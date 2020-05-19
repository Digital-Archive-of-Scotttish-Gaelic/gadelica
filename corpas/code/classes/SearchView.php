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

  public function getView() {
    return $this->_view;
  }

  public function writeSearchForm() {
    echo <<<HTML
      <form>
        <div class="form-group">
          <div class="input-group">
            <input type="text" name="search"/>
            <div class="input-group-append">
              <button name="submit" class="btn btn-primary" type="submit">search</button>
            </div>
          </div>
        </div>
        <input type="hidden" name="action" value="runSearch"/>
        <div class="form-group">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="mode" id="headwordRadio" value="headword" checked>
            <label class="form-check-label" for="headwordRadio">headword</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="mode" id="wordformRadio" value="wordform">
            <label class="form-check-label" for="wordformRadio">wordform</label>
          </div>
          <!--
          <div class="radio">
            <label class="radio-inline"><input type="radio" name="mode" id="headwordRadio" value="headword"checked>headword</label>     &nbsp;
            <label class="radio-inline"><input type="radio" name="mode" id="wordformRadio" value="wordform">wordform</label>
          </div>
          -->
        </div>
        <div id="wordformOptions" class="form-group">
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
        <div class="form-group">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="view" id="corpusViewRadio" value="corpus" checked>
            <label class="form-check-label" for="corpusViewRadio">corpus view</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="view" id="dictionaryViewRadio" value="dictionary">
            <label class="form-check-label" for="dictionaryViewRadio">dictionary view</label>
          </div>
          <!--
          <div class="radio" id="wordformView">
            <label class="radio-inline"><input type="radio" name="view" id="corpusViewRadio" value="corpus" checked>corpus view</label>&nbsp;
            <label class="radio-inline"><input type="radio" name="view" id="dictionaryViewRadio" value="dictionary">dictionary view</label>
          </div>
          -->
        </div>
        <!--<button name="submit" type="submit">go</button>-->
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
      $this->_writeViewSwitch();
    } else {
      echo <<<HTML
                <tr><th>Sorry, there were No results for <em>{$this->_search}</em></th></tr>
HTML;

    }
    $this->_writeSlipDiv();
    $this->_writeJavascript($resultTotal);
  }

  private function _writeViewSwitch() {
    $alternateView = ($this->_view == "corpus") ? "dictionary" : "corpus";
    echo <<<HTML
        <div id="viewSwitch">
            <a href="search.php?action=runSearch&search={$this->_search}&view={$alternateView}&hits={$this->_hits}">
                switch to {$alternateView} view
            </a>
        </div>
HTML;

  }

  /* print out search result as table row */
  private function _writeSearchResult($result) {
    $context = $this->_xmlFile->getContext($result["id"], 12, 12);
    $title = <<<HTML
        {$this->_xmlFile->getFilename()}{$result["id"]}<br><br>
        headword: {$result["lemma"]}<br>
        POS: {$result["pos"]}
HTML;

    echo <<<HTML
        <td style="text-align: right;">{$context["pre"]}</td>
        <td style="text-align: center;">
            <a href="viewText.php?uri={$context["uri"]}&id={$result["id"]}"
                    data-toggle="tooltip" data-html="true" title="{$title}">
                {$context["word"]}
            </a>
        </td>
        <td>{$context["post"]}</td>
        <td>
            <small><a href="#" class="slipLink" data-uri="{$context["uri"]}"
                data-headword="{$result["lemma"]}" data-pos="{$result["pos"]}"
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
    $formNum=0;
    foreach ($forms as $nextForm) {
      $formNum++;
      $array = explode('|',$nextForm);
      echo '<tr><td>' . $array[0] . '</td><td>' . $array[1] . '</td><td>';
      $i=0;
      $locations = array();
      foreach ($results as $nextResult) {
        if ($nextResult['wordform']==$array[0] && $nextResult['pos']==$array[1]) {
          $i++;
          $locations[] = $nextResult['filename'] . ' ' . $nextResult['id'];
        }
      }
      $locs = implode('|', $locations);
      echo <<<HTML
            <button href="#" id="show-{$formNum}" data-formNum="{$formNum}" data-locs="{$locs}"
                data-pos="{$array[1]}" data-lemma="{$array[0]}"
                 class="loadDictResults">
                show {$i} result(s)
            </button>
            <button href="#" id="hide-{$formNum}" data-formNum="{$formNum}" class="hideDictResults">hide results</button>
            <table id="form-{$formNum}"><tbody></tbody></table></div>
        </td></tr>
HTML;
    }
    echo <<<HTML
        </tbody>
      </table>
HTML;
    $this->_writeSlipDiv();
    $this->_writeViewSwitch();
    return;
  }

  private function _writeSlipDiv() {
    echo <<<HTML
        <div id="slip">
            filename: <span id="slipFilename"></span><br>
            id: <span id="slipId"></span><br>
            headword: <span id="slipHeadword"></span><br>
            POS: <span id="slipPOS"/></span><br><br>

            <div>
              <div>
                <span><a href="#" class="updateContext btn-link" id="decrementPre">-</a></span>
                <span><a href="#" class="updateContext" id="incrementPre">+</a></span>
              </div>
              <span data-precontextscope="20" data-postcontextscope="20" id="slipContext"></span>
              <div>
                <span><a href="#" class="updateContext btn-link" id="decrementPost">-</a></span>
                <span><a href="#" class="updateContext" id="incrementPost">+</a></span>
              </div>
            </div>

            <div>
                <label for="slipStarred">Starred: </label>
                <input type="checkbox" name="slipStarred" id="slipStarred">
            </div>

            <div>
                <label for="slipTranslation">English Translation:</label><br>
                <textarea id="slipTranslation"></textarea>
            </div>

            <div>
                <label for="slipNotes">Notes:</label><br>
                <textarea id="slipNotes"></textarea>
            </div>

            <div style="text-align: right">
                <button type="button" id="saveSlip" class="btn btn-primary">save</button>
                <a id="closeSlipLink" href="#">close</a>
            </div>

        </div>
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

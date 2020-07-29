<?php


class SearchView
{
  private $_page = 1; // results page number
  private $_hits = 0;
  private $_origin; //used to track the launch page of the search
  private $_perpage; // how many results per page
  private $_search; // search term
  private $_date; // how are results to be ordered
  private $_mode, $_case, $_accent, $_lenition, $_view; // various other input parameters from search form
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
    $this->_date        = (isset($_GET["date"])) ? $_GET["date"] : "random"; // MM: Shouldn't default be "off" here?
  }

  public function getView() {
    return $this->_view;
  }

  public function writeSearchForm() {
    $minMaxDates = SearchController::getMinMaxDates();
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
            <label class="form-check-label" for="lenitionSensitiveRadio">mutation sensitive</label>
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
        </div>
        <div class="form-group">
          <p>Order results by date:</p>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="date" id="offDateRadio" value="off" checked>
            <label class="form-check-label" for="offDateRadio">off</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="date" id="ascDateRadio" value="asc">
            <label class="form-check-label" for="ascDateRadio">ascending</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="date" id="descDateRadio" value="desc">
            <label class="form-check-label" for="ascDateRadio">descending</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="date" id="randomDateRadio" value="random">
            <label class="form-check-label" for="randomDateRadio">random</label>
          </div>
        </div>
        <div class="form-group">
            <p>Restrict by date range:</p>
            <div id="selectedDatesDisplay">{$minMaxDates["min"]}-{$minMaxDates["max"]}</div>
            <input type="hidden" class="form-control col-2" name="selectedDates" id="selectedDates">
            <div id="dateRangeSelector" class="col-6">
                <label id="dateRangeMin">{$minMaxDates["min"]}</label>
                <label id="dateRangeMax">{$minMaxDates["max"]}</label>
            </div>
        </div>
        <br>
        <div class="form-group">
            <p>Restrict by medium:</p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="medium[]" id="proseMediumCheck" value="prose" checked>
                <label class="form-check-label" for="proseMediumCheck">prose</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="medium[]" id="verseMediumCheck" value="verse" checked>
                <label class="form-check-label" for="verseMediumCheck">verse</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="medium[]" id="otherMediumCheck" value="other" checked>
                <label class="form-check-label" for="otherMediumCheck">other</label>
            </div>
        </div>
        <div class="form-group">
            <p>Restrict by part-of-speech:</p>
            {$this->_getSelectPosHtml()}
        </div>
      </form>
HTML;
    $this->_writeSearchJavascript($minMaxDates); // writes JS for year slider (maybe not necessary?)
  }

  protected function _getSelectPosHtml() {
    $distinctPOS = PartOfSpeech::getAllLabels();
    $posHtml = "";
    foreach ($distinctPOS as $abbr => $label) {
      $posHtml .= '<option value="' . $abbr . '">' . $abbr . ' (' . $label . ')</option>';
    }
    $posHtml = <<<HTML
        <select class="form-control col-3" multiple name="pos[]">
            <option value="" selected>-- all POS --</option>
            {$posHtml}
        </select>
HTML;
    return $posHtml;
  }

  public function writeSearchResults($results, $resultTotal) {
    //Add a back link to originating script
    echo <<<HTML
        <p><a href="{$this->_origin}" title="Back to search">&lt; Back to search</a></p>
HTML;

    if ($this->_view == "dictionary") {
      $this->_writeDictionaryView();
      return;
    }
    $rowNum = $this->_page * $this->_perpage - $this->_perpage + 1;
    echo <<<HTML
        <table class="table">
            <tbody>
HTML;
    if (count($results)) {
      $this->_writeResultsHeader($rowNum, $resultTotal);
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
        $this->_writeSearchResult($result, $rowNum-1);
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
    $this->_writeResultsJavascript($resultTotal);
  }

  private function _writeResultsHeader($rowNum, $resultTotal) {
    $lastDisplayedRowNum = $rowNum + $this->_perpage - 1;
    $lastDisplayedRowNum = ($lastDisplayedRowNum > $resultTotal) ? $resultTotal : $lastDisplayedRowNum;
    $html = <<<HTML
        <p>[Showing results {$rowNum}–{$lastDisplayedRowNum} of {$resultTotal} for {$this->_mode} <strong>{$this->_search}</strong>
HTML;
    if (!empty($_GET["pos"][0])) {
      $posString = implode(", ", $_GET["pos"]);
      $html .= "({$posString})";
    }
    if (count($_GET["medium"]) < 3) {
      $html .= " in " . implode(", ", $_GET["medium"]);
    }
    if ($_GET["selectedDates"]) {
      $html .= " {$_GET["selectedDates"]}";
    }
    $html .= "]</p>";
    echo $html;
  }

  private function _writeViewSwitch() {
    $alternateView = ($this->_view == "corpus") ? "dictionary" : "corpus";
    echo <<<HTML
        <div id="viewSwitch">
            <a href="search.php?action=runSearch&search={$this->_search}&view={$alternateView}&hits={$this->_hits}&origin={$this->_origin}">
                switch to {$alternateView} view
            </a>
        </div>
HTML;
  }

  /* print out search result as table row */
  private function _writeSearchResult($result, $index) {
    $context = $this->_xmlFile->getContext($result["id"], 12, 12);
    $pos = new PartOfSpeech($result["pos"]);
    $title = <<<HTML
        Headword: {$result["lemma"]}<br>
        POS: {$result["pos"]} ({$pos->getLabel()})<br>
        Date: {$result["date_of_lang"]}<br>
        Title: {$result["title"]}<br>
        Page No: {$result["page"]}<br><br>
        {$this->_xmlFile->getFilename()}<br>{$result["id"]}
HTML;
    //check if there is an existing slip for this entry
    if ($result["auto_id"] != null) {
      $slipLinkText = "view slip";
      $createSlipStyle = "";
    } else {
      $slipLinkText = "create slip";
      $createSlipStyle = "createSlipLink";
    }
    echo <<<HTML
        <td style="text-align: right;">{$context["pre"]["output"]}</td>
        <td style="text-align: center;">
            <a href="viewText.php?uri={$context["uri"]}&id={$result["id"]}"
                    data-toggle="tooltip" data-html="true" title="{$title}">
                {$context["word"]}
            </a>
        </td>
        <td>{$context["post"]["output"]}</td>
        <td>
            <small>
                <a href="#"
                    class="slipLink {$createSlipStyle}" data-uri="{$context["uri"]}"
                    data-headword="{$result["lemma"]}" data-pos="{$result["pos"]}"
                    data-id="{$result["id"]}" data-xml="{$this->_xmlFile->getFilename()}"
                    data-date="{$result["date_of_lang"]}" data-title="{$result["title"]}"
                    data-page="{$result["page"]}" data-resultindex="{$index}"
                    data-auto_id="{$result["auto_id"]}">
                    {$slipLinkText}
                </a>
            </small>
        </td>
        <td> <!-- added by MM -->
            <small>
                <a href="#" class="slipLink2 {$createSlipStyle}"
                    data-toggle="modal" data-target="#slipModal"
                    data-auto_id="{$result["auto_id"]}"
                    data-headword="{$result["lemma"]}"
                    data-pos="{$result["pos"]}"
                    data-id="{$result["id"]}"
                    data-xml="{$this->_xmlFile->getFilename()}"
                    data-uri="{$context["uri"]}"
                    data-date="{$result["date_of_lang"]}"
                    data-title="{$result["title"]}"
                    data-page="{$result["page"]}"
                    data-resultindex="{$index}">
                    {$slipLinkText}
                </a>
            </small>
        </td>
HTML;
    return;
  }

  private function _writeDictionaryView() { // added by MM
    echo '<h4>' . $_SESSION["results"][0]['lemma'] . '</h4>';
    echo '<h5>' . count($_SESSION["results"]) .' results</h5>';
    $forms = [];
    foreach ($_SESSION["results"] as $nextResult) {
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
      foreach ($_SESSION["results"] as $nextResult) {
        if ($nextResult['wordform']==$array[0] && $nextResult['pos']==$array[1]) {
          $i++;
          $locations[] = $nextResult['filename'] . ' ' . $nextResult['id'] . ' '
            . $nextResult['date_of_lang'] . ' ' . $nextResult["auto_id"] . ' '
            . str_replace(" ", "\\", $nextResult['title']) . ' ' . $nextResult["page"];
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
            <div id="slipHeader">
              <div id="slipTopRight">
                  <div id="slipChecked">
                  <!--label for="slipStarred">Starred: </label>
                  <input type="checkbox" name="slipStarred" id="slipStarred"-->
                  </div>
                  <div id="slipNumber"></div>
              </div>
              <div id="slipHeadwordContainer">
                <span id="slipHeadword"></span>
                <span id="slipWordClass"></span>
              </div>
              <div id="slipTextNum"></div>
            </div>  <!-- end slipHeader -->

            <div id="slipBody">
              <!--div>
                <span><a href="#" class="updateContext btn-link" id="decrementPre">-</a></span>
                <span><a href="#" class="updateContext" id="incrementPre">+</a></span>
              </div-->

              <span data-precontextscope="20" data-postcontextscope="20" id="slipContext"></span>

              <!--div>
                <span><a href="#" class="updateContext btn-link" id="decrementPost">-</a></span>
                <span><a href="#" class="updateContext" id="incrementPost">+</a></span>
              </div-->
              <div id="slipTranslation"></div>
            </div>

            <div id="slipFooter">
                <div id="slipTextRefContainer">
                    <div id="slipTextRef"></div>
                </div>
                <div id="slipDate"></div>
            </div>


            <input type="hidden" id="slipFilename">
            <input type="hidden" id="slipId">
            <input type="hidden" id="auto_id">
            <input type="hidden" id="slipPOS">

        <!--
            <div>
                <label for="slipNotes">Notes:</label><br>
                <div id="slipNotes"></div>
            </div>
        -->
            <div style="text-align: right">
                <button type="button" id="editSlip" class="btn btn-primary">edit</button>
                <a id="closeSlipLink" href="#">close</a>
            </div>

        </div>

       <!-- added by MM -->
        <div class="modal fade" id="slipModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <span class="text-muted" style="float:right;" id="slipNo">§</span>
              </div>
              <div class="modal-body">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                <button type="button" id="editSlip" class="btn btn-primary">edit</button>
              </div>
            </div>
          </div>
        </div>

HTML;
  }

  public function setHits($num) {
    $this->_hits = $num;
  }

  public function setOrigin($origin) {
    $this->_origin = $origin;
  }

  /**
   * Writes the Javascript required for the pagination
   */
  private function _writeResultsJavascript($resultTotal) {
    echo <<<HTML
            <script>
                $(function() {

          /*
            Date range slider
           */
             $( "#dateRange" ).slider({
                range:true,
                min: 0,
                max: 500,
                values: [ 35, 200 ],
                slide: function( event, ui ) {
                  $( "#selectedDate" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                }
              });

			     /*
				    Pagination handler
			     */
		          $("#pagination").pagination({
				          currentPage: {$this->_page},
		              items: {$resultTotal},
		              itemsOnPage: {$this->_perpage},
		              cssStyle: "light-theme",
		              onPageClick: function(pageNum) {
				            /*var url = 'search.php?action=runSearch&mode={$this->_mode}&pp={$this->_perpage}&page=' + pageNum + '&search={$this->_search}';
				            url += '&case={$this->_case}&accent={$this->_accent}&lenition={$this->_lenition}';
				            url += '&hits={$this->_hits}&view={$this->_view}';
				            url += '&date={$this->_date}';*/
                    var url = 'search.php?action=runSearch&mode={$this->_mode}&pp={$this->_perpage}&page=' + pageNum + '&search={$this->_search}';
                    url += '&hits={$this->_hits}&origin={$this->_origin}';
                    window.location.assign(url);
		              }
		          });
		      });
	       </script>
HTML;
  }

  /**
   * Writes the JS required for the search form
   * @param $params array
   */
  private function _writeSearchJavascript($params) {
    echo <<<HTML

    <script>
    $(function() {
      $( "#dateRangeSelector" ).slider({
        range:true,
        min: {$params["min"]},
        max: {$params["max"]},
        values: [ {$params["min"]}, {$params["max"]} ],
        slide: function( event, ui ) {
          var output = ui.values[0] + "-" + ui.values[1];
          $("#selectedDates").val(output);
          $('#selectedDatesDisplay').html(output);
        }
      });
    });
    </script>
HTML;
  }
}

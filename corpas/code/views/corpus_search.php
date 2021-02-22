<?php

namespace views;
use models;

class corpus_search
{
	private $_model;  //an instance of models\corpus_search

	public function __construct($model) {
		$this->_model = $model;
	}

	public function show() {
		if ($this->_model->getTerm()) {
			$this->_writeSearchResults();   //there is a search term so run the search
		} else {
			$this->_writeSearchForm();  //no search term so show the form
		}
	}

	private function _writeSearchForm() {
		$user = models\users::getUser($_SESSION["user"]);
		$minMaxDates = models\corpus_search::getMinMaxDates(); // needs a rethink for individual texts
		$dateRangeBlock = <<<HTML
			<div class="form-group">
            <p>Restrict by date range:</p>
            <div id="selectedDatesDisplay">{$minMaxDates["min"]}-{$minMaxDates["max"]}</div>
            <input type="hidden" class="form-control col-2" name="selectedDates" id="selectedDates">
            <div id="dateRangeSelector" class="col-6">
                <label id="dateRangeMin">{$minMaxDates["min"]}</label>
                <label id="dateRangeMax">{$minMaxDates["max"]}</label>
            </div>
        </div>
HTML;
		$districtBlock = $this->_getDistrictHtml();
		if ($_GET["id"]) {    //if this is a subtext don't write the date range block
			$dateRangeBlock = $districtBlock = "";
		}
		echo <<<HTML
		<ul class="nav nav-pills nav-justified" style="padding-bottom: 20px;">
HTML;
		if ($_GET["id"]=="0") {
			echo <<<HTML
			  <li class="nav-item"><a class="nav-link" href="?m=corpus&a=browse&id=0">view corpus</a></li>
			  <li class="nav-item"><div class="nav-link active">searching corpus</div></li>
HTML;
			if ($user->getSuperuser()) {
				echo <<<HTML
				  <li class="nav-item"><a class="nav-link" href="?m=corpus&a=edit&id=0">add text</a></li>
HTML;
			}
			echo <<<HTML
				<li class="nav-item"><a class="nav-link" href="?m=corpus&a=generate&id=0">corpus wordlist</a></li>
HTML;
		}
		else {
			echo <<<HTML
			  <li class="nav-item"><a class="nav-link" href="?m=corpus&a=browse&id={$_GET["id"]}">view text #{$_GET["id"]}</a></li>
			  <li class="nav-item"><div class="nav-link active">searching text #{$_GET["id"]}</div></li>
HTML;
			if ($user->getSuperuser()) {
				echo <<<HTML
			      <li class="nav-item"><a class="nav-link" href="?m=corpus&a=edit&id={$_GET["id"]}">edit text #{$_GET["id"]}</a></li>
HTML;
			}
			echo <<<HTML
				  <li class="nav-item"><a class="nav-link" href="?m=corpus&a=generate&id={$_GET["id"]}">text #{$_GET["id"]} wordlist</a></li>
HTML;
		}
		echo <<<HTML
		  </ul>
			<hr/>
      <form>
        <div class="form-group">
          <div class="input-group">
            <input type="text" name="term"/>
            <div class="input-group-append">
              <input type="hidden" name="m" value="corpus">
              <input type="hidden" name="a" value="search"/>
              <input type="hidden" name="id" value="{$_GET["id"]}">
              <button name="submit" class="btn btn-primary" type="submit">search</button>
            </div>
          </div>
        </div>
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
        <div class="form-group">
          <a href="#" id="multiWordShow">show multi-word options</a>
          <a href="#" id="multiWordHide">hide multi-word options</a>
				</div>
        <div id="multiWord" style="padding:20px; display: none;">
          <div class="form-group">
            <label for="precedingWord">preceding word</label>
            <input type="text" id="precedingWord" name="pw">
					</div>
					<div class="form-group">
	          <div class="form-check form-check-inline">
	            <input class="form-check-input" type="radio" name="preMode" id="preHeadwordRadio" value="headword" checked>
	            <label class="form-check-label" for="preHeadwordRadio">headword</label>
	          </div>
	          <div class="form-check form-check-inline">
	            <input class="form-check-input" type="radio" name="preMode" id="preWordformRadio" value="wordform">
	            <label class="form-check-label" for="preWordformRadio">wordform</label>
	          </div>
	        </div>
          <div class="form-group">
            <label for="precedingWord">following word</label>
            <input type="text" id="followingWord" name="fw">
					</div>
					<div class="form-group">
	          <div class="form-check form-check-inline">
	            <input class="form-check-input" type="radio" name="postMode" id="postHeadwordRadio" value="headword" checked>
	            <label class="form-check-label" for="postHeadwordRadio">headword</label>
	          </div>
	          <div class="form-check form-check-inline">
	            <input class="form-check-input" type="radio" name="postMode" id="postWordformRadio" value="wordform">
	            <label class="form-check-label" for="postWordformRadio">wordform</label>
	          </div>
	        </div>
				</div>  <!-- //end multiWord -->
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
          <p>Order results:</p>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="order" id="offDateRadio" value="off" checked>
            <label class="form-check-label" for="offDateRadio">off</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="order" id="ascDateRadio" value="dateAsc">
            <label class="form-check-label" for="ascDateRadio">date ascending</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="order" id="descDateRadio" value="dateDesc">
            <label class="form-check-label" for="ascDateRadio">date descending</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="order" id="randomDateRadio" value="random">
            <label class="form-check-label" for="randomDateRadio">random</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="order" id="precedingWordRadio" value="precedingWord">
            <label class="form-check-label" for="precedingWordRadio">preceding word</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="order" id="precedingWordReverseRadio" value="precedingWordReverse">
            <label class="form-check-label" for="precedingWordReverseRadio">reverse preceding word</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="order" id="followingWordRadio" value="followingWord">
            <label class="form-check-label" for="followingWordRadio">following word</label>
          </div>
        </div>
        {$dateRangeBlock}
        <br>
        {$districtBlock}
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
            <p>Restrict by importance:</p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="level[]" id="level1Check" value="1" checked>
                <label class="form-check-label" for="level1Check"><i class="fas fa-star gold"></i></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="level[]" id="level2Check" value="2" checked>
                <label class="form-check-label" for="level2Check"><i class="fas fa-star silver"></i></label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="level[]" id="level3Check" value="3" checked>
                <label class="form-check-label" for="level2Check"><i class="fas fa-star bronze"></i></label>
            </div>
        </div>
        <div class="form-group">
            <p>Restrict by part-of-speech:</p>
            {$this->_getSelectPosHtml()}
            <note><em>Select multiple options by using CTRL key (Windows) or Command key (Mac)</em></note>
        </div>
      </form>
HTML;
		$this->_writeSearchJavascript($minMaxDates); // writes JS for year slider (maybe not necessary?)
	}

	protected function _getSelectPosHtml() {
		$distinctPOS = models\partofspeech::getAllLabels();
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

	protected function _getDistrictHtml() {
		$districts = models\districts::getAllDistrictsInfo();
		foreach ($districts as $district) {
			$id = $district["id"];
			$districtsHtml .= <<<HTML
				<div class="form-check form-check-inline">
            <input class="form-check-input district" type="checkbox" name="district[]" id="district{$id}Check" value="{$id}" checked>
            <label class="form-check-label" for="district{$id}Check">
              {$district["name"]}
						</label>
        </div>
HTML;
		}
		$html = <<<HTML
			<div class="form-group">
            <p>Restrict by location:</p>
            <div>
              {$districtsHtml}
            </div>
            <div>
              <a href="#" id="uncheckAllDistricts">uncheck all</a>
              <a href="#" id="checkAllDistricts">check all</a>
						</div>
        </div>
HTML;
		return $html;
	}

	private function _writeSearchResults() {
		$results = $this->_model->getResults();
		$resultTotal = $this->_model->getHits();
		models\collection::writeSlipDiv();
		//Add a back link to originating script
		echo <<<HTML
        <p><a href="index.php?m=corpus&a=search&id={$_GET["id"]}" title="Back to search">&lt; Back to search</a></p>
HTML;

		if ($this->_model->getView() == "dictionary") {
			$this->_writeDictionaryView();
			return;
		}
		$rowNum = $this->_model->getPage() * $this->_model->getPerPage() - $this->_model->getPerPage() + 1;
		echo <<<HTML
        <table class="table">
            <tbody>
HTML;
		if (count($results)) {
			$this->_writeResultsHeader($rowNum, $resultTotal);
			$filename = "";
			foreach ($results as $result) {
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
                <tr><th>Sorry, there were No results for <em>{$this->_model->getTerm()}</em></th></tr>
HTML;

		}
		$this->_writeResultsJavascript();
	}

	private function _writeResultsHeader($rowNum, $resultTotal) {
		$lastDisplayedRowNum = $rowNum + $this->_model->getPerPage() - 1;
		$lastDisplayedRowNum = ($lastDisplayedRowNum > $resultTotal) ? $resultTotal : $lastDisplayedRowNum;
		$html = <<<HTML
        <p>[Showing results {$rowNum}–{$lastDisplayedRowNum} of {$resultTotal} 
        for {$this->_model->getMode()} <strong>{$this->_model->getTerm()}</strong>
HTML;
		if (!empty($_GET["pos"][0])) {
			$posString = implode(", ", $_GET["pos"]);
			$html .= "({$posString})";
		}
		if (isset($_GET["medium"]) && count($_GET["medium"]) < 3) {
			$html .= " in " . implode(", ", $_GET["medium"]);
		}
		if ($_GET["selectedDates"]) {
			$html .= " {$_GET["selectedDates"]}";
		}
		$html .= "]</p>";
		echo $html;
	}

	private function _writeViewSwitch() {
		$alternateView = ($this->_model->getView() == "corpus") ? "dictionary" : "corpus";
		$url = "index.php?m=corpus&a=search&mode={$this->_model->getMode()}";
		$url .= "&term={$this->_model->getTerm()}&id={$this->_model->getId()}";
		$url .= "&view={$alternateView}&hits={$this->_model->getHits()}";
		echo <<<HTML
        <div id="viewSwitch">
            <a href="{$url}">
                switch to {$alternateView} view
            </a>
        </div>
HTML;
	}

	/* print out search result as table row */
	private function _writeSearchResult($result, $index) {
		$context = $result["context"];
		$pos = new models\partofspeech($result["pos"]);

		/**
		 * !Experimental short title code - to be reconsidered and possibly moved SB
		 */
		$shortTitleElems = explode(' ', $result["title"]);
		foreach ($shortTitleElems as $elem) {
			if ($elem == '–') {
				break;
			}
			$shortTitle .= mb_substr($elem, 0, 1);
		}
		/* --- */

		$title = <<<HTML
        Headword: {$result["lemma"]}<br>
        POS: {$result["pos"]} ({$pos->getLabel()})<br>
        Date: {$result["date_of_lang"]}<br>
        Title: {$result["title"]}<br>
        Page No: {$result["page"]}<br><br>
        {$result["filename"]}<br>{$result["id"]}
HTML;
		//check if there is an existing slip for this entry
		$slipUrl = "#";
		$slipClass = "slipLink2";
		$modalCode = "";
		if ($result["auto_id"] != null) {
			$slipLinkText = "view";
			$createSlipStyle = "";
			$modalCode = 'data-toggle="modal" data-target="#slipModal"';
			$dataUrl = "";
		} else {    //there is no slip so show link for adding one
			$dataUrl = "index.php?m=collection&a=add&filename=" . $result["filename"] . "&wid=".$result["id"];
			$dataUrl .= "&headword=".$result["lemma"] . "&pos=" . $result["pos"];
			$slipLinkText = "add";
			$createSlipStyle = "createSlipLink";
			$slipClass = "editSlipLink";
		}
		$textNum = stristr($result["filename"], "_", true);
		echo <<<HTML
				<td>{$result["date_of_lang"]}</td>
				<td>#{$textNum} {$shortTitle}</td>
        <td style="text-align: right;">{$context["pre"]["output"]}</td>
        <td style="text-align: center;">
            <a href="?m=corpus&a=browse&id={$result["tid"]}&wid={$result["id"]}"
                    data-toggle="tooltip" data-html="true" title="{$title}">
                {$context["word"]}
            </a>
        </td>
        <td>{$context["post"]["output"]}</td>
        <td> <!-- the slip link -->
            <small>
                <a href="{$slipUrl}" data-url="{$dataUrl}" class="{$slipClass} {$createSlipStyle}"
                    {$modalCode}
                    data-auto_id="{$result["auto_id"]}"
                    data-headword="{$result["lemma"]}"
                    data-pos="{$result["pos"]}"
                    data-id="{$result["id"]}"
                    data-xml="{$result["filename"]}"
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
		$_GET["pp"] = null;   //don't limit the results - fetch them all
		//instantiate a new model to set the per page to null
		$model = new models\corpus_search($_GET);
		$searchResults = $model->getResults();
		echo '<h4>' . $searchResults[0]['lemma'] . '</h4>';
		echo '<h5>' . $this->_model->getHits() .' results</h5>';
		$forms = [];
		foreach ($searchResults as $nextResult) {
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
			foreach ($searchResults as $nextResult) {
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
		models\collection::writeSlipDiv();
		$this->_writeViewSwitch();
		return;
	}

	public function setHits($num) {
		$this->_hits = $num;
	}

	/**
	 * Writes the Javascript required for the pagination
	 */
	private function _writeResultsJavascript() {
		echo <<<HTML
        <script>
        $(function() {
                  
          /*
            Open the add new slip form in a new tab        
           */
             $('.createSlipLink').on('click', function() {
               var url = $(this).attr('data-url');
               var win = window.open(url, '_blank');
               if (win) {
						      //Browser has allowed it to be opened
						      win.focus();
						    } else {
						      //Browser has blocked it
						      alert('Please allow popups for this website');
						    }
             });
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
				          currentPage: {$this->_model->getPage()},
		              items: {$this->_model->getHits()},
		              itemsOnPage: {$this->_model->getPerPage()},
		              cssStyle: "light-theme",
		              onPageClick: function(pageNum) {
				            var url = 'index.php?{$_SERVER["QUERY_STRING"]}&page=' + pageNum;
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
      $('#multiWordHide').hide();
      $('#multiWordShow').on('click', function () {
        $('#multiWord').show();
        $('#multiWordShow').hide();
        $('#multiWordHide').show();
      });
      $('#multiWordHide').on('click', function () {
        $('#multiWord').hide();
        $('#multiWordHide').hide();
        $('#multiWordShow').show();
      });
      
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
      
      $('#uncheckAllDistricts').on('click', function() {
        $('.district').prop('checked', false);
      });
      
      $('#checkAllDistricts').on('click', function() {
        $('.district').prop('checked', true);
      });
      
    });
    </script>
HTML;
	}
}

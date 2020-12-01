<?php

namespace views;
use models;

class slip
{
  private $_slip;   //an instance of the Slip class

  public function __construct($slip) {
    $this->_slip = $slip;
  }

  public function show($action) {
		switch ($action) {
			case "edit":
				$this->_writeEditForm();
				break;
		}
  }

  private function _writeEditForm() {
  	$checked = $this->_slip->getStarred() ? "checked" : "";
    $locked = $this->_slip->getLocked();
    $lockHide = $unlockHide = "";
    if ($locked) {
    	$unlockHide = "d-none";
    } else {
	    $lockHide = "d-none";
    }
    echo <<<HTML
				{$this->_writeContext()}
				{$this->_writeCollocatesView()}
        <div class="form-group" id="slipChecked">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="starred" id="slipStarred" {$checked}>
            <label class="form-check-label" for="slipStarred">checked</label>
          </div>
        </div>
         <div>
            headword: <span id="slipHeadword">{$_REQUEST["headword"]}</span>
        </div>
HTML;
    $this->_writePartOfSpeechSelects();
	  $this->_writeSenseCategories();
    echo <<<HTML
        <div class="form-group">
          <label for="slipTranslation">English translation:</label>
          <textarea class="form-control" name="slipTranslation" id="slipTranslation" rows="3">{$this->_slip->getTranslation()}</textarea>
          <script>
            CKEDITOR.replace('slipTranslation', {
              contentsCss: 'https://dasg.ac.uk/gadelica/corpas/code/css/ckCSS.css',
              customConfig: 'https://dasg.ac.uk/gadelica/corpas/code/js/ckConfig.js'
            });
          </script>
        </div>
        <div class="form-group">
          <label for="slipNotes">Notes:</label>
          <textarea class="form-control" name="slipNotes" id="slipNotes" rows="3">{$this->_slip->getNotes()}</textarea>
          <script>
            CKEDITOR.replace('slipNotes', {
              contentsCss: 'https://dasg.ac.uk/gadelica/corpas/code/css/ckCSS.css',
              customConfig: 'https://dasg.ac.uk/gadelica/corpas/code/js/ckConfig.js'
            });
          </script>
        </div>
        <div class="form-group">
          <div class="input-group">
            <input type="hidden" name="filename" value="{$_REQUEST["filename"]}">
            <input type="hidden" name="id" value="{$_REQUEST["wid"]}">
            <input type="hidden" id="locked" name="locked" value="{$locked}";
            <input type="hidden" id="auto_id" name="auto_id" value="{$this->_slip->getAutoId()}">
            <input type="hidden" id="pos" name="pos" value="{$_REQUEST["pos"]}">
            <input type="hidden" id="preContextScope" name="preContextScope" value="{$this->_slip->getPreContextScope()}">
            <input type="hidden" id="postContextScope" name="postContextScope" value="{$this->_slip->getPostContextScope()}">
            <input type="hidden" name="action" value="save"/>
            <!--div>
              <a data-toggle="tooltip" title="Click to unlock" class="{$lockHide} lockBtn locked btn btn-large btn-danger" href="#">
                <i class="fa fa-lock" aria-hidden="true"></i></a>
              <a data-toggle="tooltip" title="Click to lock" class="{$unlockHide} lockBtn unlocked btn btn-large btn-success" href="#">
                <i class="fa fa-unlock" aria-hidden="true"></i></a>
						</div-->
            <div class="mx-2">
              <button name="close" class="windowClose btn btn-secondary">close</button>
              <button name="submit" id="savedClose" class="btn btn-primary">save</button>
             </div>
          </div>
        </div>
HTML;
    $this->_writeUpdatedBy();
    $this->_writeFooter();;
    $this->_writeSavedModal();
  }

  private function _writeUpdatedBy() {
    $email = $this->_slip->getLastUpdatedBy();
    if (!$email) {
      return;
    }
    $user = models\users::getUser($email);
    $time = $this->_slip->getLastUpdated();
    echo <<<HTML
        <div>
            <p>Last updated {$time} by {$user->getFirstName()} {$user->getLastName()}</p>
        </div>
HTML;
  }

  private function _writeSavedModal() {
    echo <<<HTML
        <div id="slipSavedModal" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <h2>Slip Saved</h2>
                </div>
            </div>
          </div>
        </div>
HTML;
  }

  private function _writeFooter() {
    $pos = new models\partofspeech($_REQUEST["pos"]);
    $label = $_REQUEST["pos"] ? " ({$pos->getLabel()})" : "";
    echo <<<HTML
        <div>
            slip ID:<span id="auto_id">{$this->_slip->getAutoId()}</span><br>
            POS tag:<span id="slipPOS">{$_REQUEST["pos"]}{$label}</span><br><br>
            filename: <span id="slipFilename">{$this->_slip->getFilename()}</span><br>
            id: <span id="wordId">{$_REQUEST["wid"]}</span><br>
        </div>
HTML;
    $this->_writeJavascript();
  }

  private function _writePartOfSpeechSelects() {
    echo $this->_writeWordClassesSelect();
    $props = $this->_slip->getSlipMorph()->getProps();  //the morph data
    $relations = array("numgen", "case", "mode", "fin_person", "imp_person", "fin_number",
	    "imp_number", "status", "tense", "mood", "prep_mode", "prep_person", "prep_number", "prep_gender");
    $options["numgen"] = array("masculine singular", "feminine singular", "plural", "singular (gender unclear)",
      "feminine dual", "unclear");
    $options["case"] = array("nominative", "genitive", "dative", "unclear");
    $options["mode"] = array("unclear mode", "imperative", "finite", "verbal noun");
	  $options["imp_person"] = array("second person", "first person", "third person", "unclear person");
    $options["fin_person"] = array("unmarked person", "first person", "second person", "third person");
    $options["imp_number"] = array("singular", "plural", "unclear number");
    $options["fin_number"] = array("unmarked number", "singular", "plural");
    $options["status"] = array("unclear status", "dependent", "independent", "relative");
    $options["tense"] = array("unclear tense", "present", "future", "past", "conditional");
    $options["mood"] = array("active", "impersonal", "unclear mood");
    //prepositions
	  $options["prep_mode"] = array("basic", "augmented", "conjugated", "possessive", "unclear mode");
	  $options["prep_person"] = array("first person", "second person", "third person", "unclear person");
	  $options["prep_number"] = array("singular", "plural", "unclear number");
	  $options["prep_gender"] = array("masculine", "feminine", "unclear gender");
		//create the HTML options for each relation
	  $optionsHtml = array();
    foreach ($relations as $relation) {
    	$optionsHtml[$relation] = "";
    	foreach ($options[$relation] as $option) {
    		$selected = $option == $props[$relation] ? "selected" : "";
    		$optionsHtml[$relation] .= <<<HTML
					<option value="{$option}" {$selected}>{$option}</option>
HTML;
	    }
    }
    $nounSelectHide = $this->_slip->getWordClass() == "noun" ? "" : "hide";
    $verbSelectHide = $this->_slip->getWordClass() == "verb" ? "" : "hide";
    $prepSelectHide = $this->_slip->getWordClass() == "preposition" ? "" : "hide";
	  $impVerbSelectHide = $props["mode"] == "imperative" ? "" : "hide";
	  $finVerbSelectHide = $props["mode"] == "finite" ? "" : "hide";
    $verbalNounHide = $props["mode"] == "verbal noun" ? "hide" : "";
    $conjPosPrepHide = $props["prep_mode"] != "conjugated" && $props["prep_mode"] != "possessive" ? "hide" : "";
    $genderPrepHide = "hide";
    //show/hide gender dropdown
    if ($conjPosPrepHide != "hide") {
    	$genderPrepHide = ($props["prep_person"] != "third person") || ($props["prep_number"] != "singular") ? "hide" : "";
    }
    echo <<<HTML
        <div class="editSlipSectionContainer">
          <h5>Morphological information</h5>
            <div id="prepSelects" class="{$prepSelectHide}">
              <div class="row form-group form-inline">
                <label for="posPrepMode" class="col-form-label col-sm-1">Mode:</label>
                <select name="prep_mode" id="posPrepMode" class="form-control col-2">
                  {$optionsHtml["prep_mode"]}
                </select>
              </div>
              <span id="conjPosPrepOptions" class="{$conjPosPrepHide}">
                <div class="row form-group form-inline">
                  <label for="posPrepPerson" class="col-form-label col-sm-1">Person:</label>
                  <select name="prep_person" id="posPrepPerson" class="form-control col-2">
                    {$optionsHtml["prep_person"]}
                  </select>
                </div>
                <div class="row form-group form-inline">
                  <label for="posPrepNumber" class="col-form-label col-sm-1">Number:</label>
                  <select name="prep_number" id="posPrepNumber" class="form-control col-2">
                    {$optionsHtml["prep_number"]}
                  </select>
                </div>
                  <span id="genderPrepOptions" class="{$genderPrepHide}">
                    <div class="row form-group form-inline">
                      <label for="posPrepGender" class="col-form-label col-sm-1">Gender:</label>
                      <select name="prep_gender" id="posPrepGender" class="form-control col-2">
                        {$optionsHtml["prep_gender"]}
											</select>
										</div>
									</span>
								</span>
            </div>
            <div id="nounSelects" class="{$nounSelectHide}">
                <div class="row form-group form-inline">
	                <label for="posNumberGender" class="col-form-label col-sm-1">Number:</label>
	                <select name="numgen" id="posNumberGender" class="form-control col-2">
	                  {$optionsHtml["numgen"]}
	                </select>
	              </div>
	              <div class="row form-group form-inline">
	                <label for="posCase" class="col-form-label col-sm-1">Case:</label>
	                <select name="case" id="posCase" class="form-control col-2">
	                  {$optionsHtml["case"]}
	                </select>
	              </div>
            </div>
            <div id="verbSelects" class="{$verbSelectHide}">
                <div class="row form-group form-inline">
	                <label for="posMode" class="col-form-label col-sm-1">Mode:</label>
	                <select name="mode" id="posMode" class="form-control col-2">
	                  {$optionsHtml["mode"]}
	                </select>
	              </div>
                <span id="nonVerbalNounOptions" class="{$verbalNounHide}">
                  <span id="imperativeVerbOptions" class="{$impVerbSelectHide}">
                    <div class="row form-group form-inline">
	                    <label for="posImpPerson" class="col-form-label col-sm-1">Person:</label>
		                  <select name="imp_person" id="posImpPerson" class="form-control col-2">
		                    {$optionsHtml["imp_person"]}
		                  </select>
		                </div>
		                <div class="row form-group form-inline">
		                  <label for="posImpNumber" class="col-form-label col-sm-1">Number:</label>
		                  <select name="imp_number" id="posImpNumber" class="form-control col-2">
		                    {$optionsHtml["imp_number"]}
		                  </select>
		                </div>
	                </span>
	                <span id="finiteVerbOptions" class="{$finVerbSelectHide}">
	                  <div class="row form-group form-inline">
	                    <label for="posFinPerson" class="col-form-label col-sm-1">Person:</label>
		                  <select name="fin_person" id="posFinPerson" class="form-control col-2">
		                    {$optionsHtml["fin_person"]}
		                  </select>
		                </div>
		                <div class="row form-group form-inline">
		                  <label for="posFinNumber" class="col-form-label col-sm-1">Number:</label>
		                  <select name="fin_number" id="posFinNumber" class="form-control col-2">
		                    {$optionsHtml["fin_number"]}
		                  </select>
		                </div>
		                <div class="row form-group form-inline">
		                  <label for="posStatus" class="col-form-label col-sm-1">Status:</label>
		                  <select name="status" id="posStatus" class="form-control col-2">
		                    {$optionsHtml["status"]}
		                  </select>
		                </div>
		                <div class="row form-group form-inline">
	                    <label for="posTense" class="col-form-label col-sm-1">Tense:</label>
	                    <select name="tense" id="posTense" class="form-control col-2">
	                      {$optionsHtml["tense"]}
	                    </select>
	                  </div>
	                  <div class="row form-group form-inline">
	                    <label for="posMood" class="col-form-label col-sm-1">Mood:</label>
	                    <select name="mood" id="posMood" class="form-control col-2">
	                      {$optionsHtml["mood"]}
	                    </select>
	                  </div>
                  </span>
                </span>
            </div>
        </div>
HTML;

  }

  private function _writeWordClassesSelect(){
    $classes = $this->_slip->getWordClasses();
    $optionHtml = "";
    foreach ($classes as $class => $posArray) {
      $selected = $class == $this->_slip->getWordClass() ? "selected" : "";
      $optionHtml .= <<<HTML
        <option value="{$class}" {$selected}>{$class}</option>
HTML;
    }
    $html = <<<HTML
        <div id="wordClassSelect" class="editSlipSectionContainer form-group form-inline">
          <label for="wordClass" class="col-form-label col-sm-2"><h5>Part-of-speech:</h5></label>
          <select name="wordClass" id="wordClass" class="form-control col-3">
            {$optionHtml}
          </select>
      </div>
HTML;
    return $html;
  }

  private function _writeSenseCategories()
  {
    $categories = models\sensecategories::getAllUnusedCategories($this->_slip->getAutoId(),
      $_REQUEST["headword"], $this->_slip->getWordClass());
    $dropdownHtml = '<option data-category="">-- select a category --</option>';
    foreach ($categories as $cat) {
      $dropdownHtml .= <<<HTML
        <option data-category="{$cat}" value="{$cat}">{$cat}</option>
HTML;
    }
    $savedCategories = $this->_slip->getSenseCategories();
    $savedCatHtml = "";
    foreach ($savedCategories as $category) {
      $savedCatHtml .= <<<HTML
        <li class="badge badge-success" data-category="{$category}">{$category} <a class="badge badge-danger deleteCat">X</a></li>
HTML;
    }
    echo <<<HTML
        <div class="editSlipSectionContainer">
          <h5>Sense Categories</h5>
          <div class="form-group row">
            <div class="col-md-3">
                  <label for="senseCategorySelect">Choose existing sense category:</label>
            </div>
            <div>
                <select id="senseCategorySelect">{$dropdownHtml}</select>
            </div>
            <div class="col-md-1">
                  <button type="button" class="form-control btn btn-primary" id="chooseSenseCategory">Add</button>
              </div>
          </div>
          <div class="form-group row">
              <div class="col-md-3">
                  <label for="senseCategory">Assign to new sense category:</label>
              </div>
              <div class="col-md-3">
                  <input type="text" class="form-control" id="senseCategory">
              </div>
              <div class="col-md-1">
                  <button type="button" class="form-control btn btn-primary" id="addSenseCategory">Add</button>
              </div>
          </div>
          <div>
            <ul id="senseCategories">
                {$savedCatHtml}
            </ul>
          </div>
        </div>
HTML;
  }

  private function _writeContext() {
    $handler = new models\xmlfilehandler($this->_slip->getFilename());
    $preScope = $this->_slip->getPreContextScope();
    $postScope = $this->_slip->getPostContextScope();
    $context = $handler->getContext($this->_slip->getId(), $preScope, $postScope, true, false);
    $preHref = "href=\"#\"";
    //check for start/end of document
    if (isset($context["prelimit"])) {
      $preScope = $context["prelimit"];
      $preHref = "";
    }
    $contextHtml = $context["pre"]["output"];
    if ($context["pre"]["endJoin"] != "right" && $context["pre"]["endJoin"] != "both") {
      $contextHtml .= ' ';
    }
    $contextHtml .= <<<HTML
      <mark id="slipWordInContext" data-headwordid="{$context["headwordId"]}">{$context["word"]}</mark>
HTML;
    if ($context["post"]["startJoin"] != "left" && $context["post"]["startJoin"] != "both") {
      $contextHtml .= ' ';
    }
    $contextHtml .= $context["post"]["output"];
    echo <<<HTML
            <div id="slipContextContainer" class="editSlipSectionContainer">
              <div class="floatRight">
                <a href="#" class="btn btn-success" id="showCollocatesView">collocates view</a>
              </div>
              <h5>Adjust citation context</h5>
              <div>
								<a class="updateContext" id="decrementPre"><i class="fas fa-minus"></i></a>
								<a {$preHref} class="updateContext" id="incrementPre"><i class="fas fa-plus"></i></a>
              </div>
              <span data-precontextscope="{$preScope}" data-postcontextscope="{$postScope}" id="slipContext" class="slipContext">
                {$contextHtml}
              </span>
              <div>
                <a class="updateContext" id="decrementPost"><i class="fas fa-minus"></i></a>
								<a class="updateContext" id="incrementPost"><i class="fas fa-plus"></i></a>
              </div>
            </div>
HTML;
  }

	private function _writeCollocatesView() {
		$handler = new models\xmlfilehandler($this->_slip->getFilename());
		$preScope = $this->_slip->getPreContextScope();
		$postScope = $this->_slip->getPostContextScope();
		$context = $handler->getContext($this->_slip->getId(), $preScope, $postScope, true, true);

		$contextHtml = $context["pre"]["output"];
		if ($context["pre"]["endJoin"] != "right" && $context["pre"]["endJoin"] != "both") {
			$contextHtml .= ' ';    //  <div style="display:inline;">
		}
		$contextHtml .= <<<HTML
			<span>{$context["word"]}</span>
HTML;
		if ($context["post"]["startJoin"] != "left" && $context["post"]["startJoin"] != "both") {
			$contextHtml .= ' ';  //  <div style="display:inline;">
		}
		$contextHtml .= $context["post"]["output"];
		echo <<<HTML
            <div id="slipCollocatesContainer" class="hide editSlipSectionContainer">
              <div class="floatRight">
                <a class="btn btn-success" href="#" id="showCitationView">citation view</a>
              </div>
              <h5>Tag citation collocates</h5>
              <span class="slipContext">
                {$contextHtml}
              </span>
            </div>
HTML;
	}

  private function _writeJavascript() {
    echo <<<HTML
        <script>     
            //refresh the results page that brought us here to change "create slip" to "view slip"
		        window.opener.document.location.reload(true);
               
            $('.lockBtn').on('click', function (e) {
              e.preventDefault();
              $(this).addClass('d-none');
              $(this).siblings().removeClass('d-none');
              if ($(this).hasClass('unlocked')) {
                $('#locked').val('1');
              } else {
                $('#locked').val('0');
              }  
            });
            
            $('#showCitationView').on('click', function () {
              $('#slipCollocatesContainer').hide();
              $('#slipContextContainer').show();
            });

            $('#showCollocatesView').on('click', function () {
              console.log('hit');
              $('#slipContextContainer').hide();
              $('#slipCollocatesContainer').show();
            });

            /*
              Show the collocate dropdown
             */
            $('.collocateLink').on('click', function () {
              $('.dropdown-item').removeClass('disabled');  //clear any previous entries
              var wordId = $(this).parent().attr('data-wordid');
              var filename = '{$this->_slip->getFilename()}';
              var url = 'ajax.php?action=getGrammarInfo&id='+wordId+'&filename='+filename;
              $.getJSON(url, function(data) {
                $('.collocateHeadword').text(data.lemma);
                if (data.grammar) {
                  var id = data.grammar.replace(' ', '_') + '_' + wordId;
                  $('#'+id).addClass('disabled');
                }
              });
            });

            /*
              Save the collocate grammar info
             */
            $('.collocateGrammar').on('click', function () {
              var wordId = $(this).parents('div.collocate').attr('data-wordid');
              $(this).parent().siblings('.collocateLink').addClass('existingCollocate');
              var filename = '{$this->_slip->getFilename()}';
              var headwordId = $('#slipWordInContext').attr('data-headwordid');
              var slipId = '{$this->_slip->getAutoId()}';
              var url = 'ajax.php?action=saveLemmaGrammar&id='+wordId+'&filename='+filename;
              url += '&headwordId='+headwordId+'&slipId='+slipId+'&grammar='+$(this).text();
              $.getJSON(url, function(data) {
                $('.collocateHeadword').text(data.lemma);
              });
            });

            $("#chooseSenseCategory").on('click', function () {
              var elem = $( "#senseCategorySelect option:selected" );
              var category = elem.text();
              if (elem.attr('data-category') == "") {
                return false;
              }
              var html = '<li class="badge badge-success" data-category="' + category + '">' + category;
              html += ' <a class="badge badge-danger deleteCat">X</a></li>';
              $('#senseCategories').append(html);
              elem.remove();
              var data = {action: 'saveCategory', slipId: '{$this->_slip->getAutoId()}',
                categoryName: category}
              $.post("ajax.php", data, function (response) {
                console.log(response);        //TODO: add some response code on successful save
              });
              console.log(category);
            });

            $(document).on('click', '#addSenseCategory', function () {
              var newCategory = $('#senseCategory').val();
              if (newCategory == "") {
                return false;
              }
              var html = '<li class="badge badge-success" data-category="' + newCategory + '">' + newCategory;
              html += ' <a class="badge badge-danger deleteCat">X</a></li>';
              $('#senseCategories').append(html);
              $('#senseCategory').val('');
              var data = {action: 'saveCategory', slipId: '{$this->_slip->getAutoId()}',
                categoryName: newCategory}
              $.post("ajax.php", data, function (response) {
                console.log(response);        //TODO: add some response code on successful save
              });
            });

            $(document).on('click', '.deleteCat', function () {
              var category = $(this).parent().attr('data-category');
              $(this).parent().remove();
              var html = '<option data-category="' + category + '">' + category + '</option>';
              $('#senseCategorySelect').append(html);
              var data = {action: 'deleteCategory', slipId: '{$this->_slip->getAutoId()}',
                categoryName: category}
              $.post("ajax.php", data, function (response) {
                console.log(response);        //TODO: add some response code on successful save
              });
            });

            $('#wordClass').on('change', function() {
              var wordclass = $(this).val();
              switch (wordclass) {
                case "verb":
                  $('#verbSelects').show();
                  $('#nonVerbalNounOptions').show();
                  $('#nounSelects').hide();
                  $('#prepSelects').hide();
                  break;
                case "noun":
                  $('#nounSelects').show();
                  $('#verbSelects').hide();
                  $('#prepSelects').hide();
                  break;
                case "preposition":
                  $('#prepSelects').show();
                  $('#nounSelects').hide();
                  $('#verbSelects').hide();
                  break;
                default:
                  $('#nounSelects').hide();
                  $('#verbSelects').hide();
                  $('#prepSelects').hide();
              }
              //update the sense categories
              $('#senseCategorySelect').empty();
              $('#senseCategorySelect').append('<option data-category="">-- select a category --</option>');
              var url = 'ajax.php?action=getSenseCategories';
              url += '&slipId={$_GET["id"]}&headword={$_GET["headword"]}&wordclass=' + wordclass;
              $.getJSON(url, function (data) {
                  $.each(data, function (index, value) {
                    $('#senseCategorySelect').append('<option data-category="' + value + '" value="' + value + '">' + value + '</option>');
                  });
              });
            });

            $('#posMode').on('change', function() {
              var mode = $(this).val();
              if(mode == "verbal noun" || mode == "unclear mode") {
                $('#nonVerbalNounOptions').hide();
              } else {
                $('#nonVerbalNounOptions').show();
              }
              if (mode == "imperative") {
                $('#imperativeVerbOptions').show();
                $('#finiteVerbOptions').hide();
              } else if (mode == "finite") {
                $('#finiteVerbOptions').show();
                $('#imperativeVerbOptions').hide();
              }
            });
            
            $('#posPrepMode').on('change', function () {
              var mode = $(this).val();
              if (mode == "conjugated" || mode == "possessive") {
                $('#conjPosPrepOptions').show();
              } else {
                $('#conjPosPrepOptions').hide();
              }
            });
            
            $('#posPrepPerson').on('change', function () {
              var person = $(this).val();
              var number = $('#posPrepNumber').val();
              if (person == 'third person' && number == 'singular') {
                $('#genderPrepOptions').show();
              } else {
                $('#genderPrepOptions').hide();
              }
            });
            
            $('#posPrepNumber').on('change', function () {
              var number = $(this).val();
              var person = $('#posPrepPerson').val();
              if (person == 'third person' && number == 'singular') {
                $('#genderPrepOptions').show();
              } else {
                $('#genderPrepOptions').hide();
              }
            });
        </script>
HTML;
  }
}

<?php


class SlipView
{
  private $_slip;   //an instance of the Slip class

  public function __construct($slip) {
    $this->_slip = $slip;
  }

  public function writeEditForm() {
    $checked = $this->_slip->getStarred() ? "checked" : "";
    echo <<<HTML
        <div>
            {$this->_writeContext()}
        </div>
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
    echo <<<HTML
        {$this->_writeSenseCategories()}
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
            <input type="hidden" name="id" value="{$_REQUEST["id"]}">
            <input type="hidden" id="auto_id" name="auto_id" value="{$this->_slip->getAutoId()}">
            <input type="hidden" id="pos" name="pos" value="{$_REQUEST["pos"]}">
            <input type="hidden" id="preContextScope" name="preContextScope" value="{$this->_slip->getPreContextScope()}">
            <input type="hidden" id="postContextScope" name="postContextScope" value="{$this->_slip->getPostContextScope()}">
            <input type="hidden" name="action" value="save"/>
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
    $user = Users::getUser($email);
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
    $pos = new PartOfSpeech($_REQUEST["pos"]);
    $label = $_REQUEST["pos"] ? " ({$pos->getLabel()})" : "";
    echo <<<HTML
        <div>
            slip ID:<span id="auto_id">{$_REQUEST["auto_id"]}</span><br>
            POS tag:<span id="slipPOS">{$_REQUEST["pos"]}{$label}</span><br><br>
            filename: <span id="slipFilename">{$this->_slip->getFilename()}</span><br>
            id: <span id="slipId">{$this->_slip->getId()}</span><br>
        </div>
HTML;
    $this->_writeJavascript();
  }

  private function _writePartOfSpeechSelects() {
    echo $this->_writeWordClassesSelect();
    $props = $this->_slip->getSlipMorph()->getProps();
    $numgenOptions = array("masculine singular", "feminine singular", "plural", "singular (gender unclear)",
      "feminine dual", "unclear");
    $caseOptions = array("nominative", "genitive", "dative", "unclear");
    $statusOptions = array("dependent", "independent", "relative", "verbal noun", "unclear");
    $tenseOptions = array("present", "future", "past", "conditional", "unclear");
    $moodOptions = array("active", "passive", "unclear");
    $numgenOptionHtml = $caseOptionHtml = $statusOptionHtml = $tenseOptionHtml = $moodOptionHtml = "";
    foreach ($numgenOptions as $numgen) {
      $selected = $numgen == $props["numgen"] ? "selected" : "";
      $numgenOptionHtml .= <<<HTML
        <option value="{$numgen}" {$selected}>{$numgen}</option>
HTML;
    }
    foreach ($caseOptions as $case) {
      $selected = $case == $props["case"] ? "selected" : "";
      $caseOptionHtml .= <<<HTML
        <option value="{$case}" {$selected}>{$case}</option>
HTML;
    }
    foreach ($statusOptions as $status) {
      $selected = $status == $props["status"] ? "selected" : "";
      $statusOptionHtml .= <<<HTML
        <option value="{$status}" {$selected}>{$status}</option>
HTML;
    }
    foreach ($tenseOptions as $tense) {
      $selected = $tense== $props["tense"] ? "selected" : "";
      $tenseOptionHtml .= <<<HTML
        <option value="{$tense}" {$selected}>{$tense}</option>
HTML;
    }
    foreach ($moodOptions as $mood) {
      $selected = $mood == $props["mood"] ? "selected" : "";
      $moodOptionHtml .= <<<HTML
        <option value="{$mood}" {$selected}>{$mood}</option>
HTML;
    }
    $nounSelectHide = $this->_slip->getWordClass() == "noun" ? "" : "hide";
    $verbSelectHide = $this->_slip->getWordClass() == "verb" ? "" : "hide";
    $verbalNounHide = $props["status"] == "verbal noun" ? "hide" : "";
    echo <<<HTML
        <div>
            <div id="nounSelects" class="{$nounSelectHide}">
                <label for="posNumberGender">Number:</label>
                <select name="numgen" id="posNumberGender" class="form-control col-2">      
                  {$numgenOptionHtml}
                </select>  
                <label for="posCase">Case:</label>
                <select name="case" id="posCase" class="form-control col-2">      
                  {$caseOptionHtml}
                </select>      
            </div>
            <div id="verbSelects" class="{$verbSelectHide}">
                <label for="posStatus">Status:</label>
                <select name="status" id="posStatus" class="form-control col-2">      
                  {$statusOptionHtml}
                </select>  
                <span id="nonVerbalNounOptions" class="{$verbalNounHide}">
                  <label for="posTense">Tense:</label>
                  <select name="tense" id="posTense" class="form-control col-2">      
                    {$tenseOptionHtml}
                  </select>   
                  <label for="posMood">Mood:</label>
                  <select name="mood" id="posMood" class="form-control col-2">      
                    {$moodOptionHtml}
                  </select>
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
        <div id="wordClassSelect" class="form-group">
          <label for="wordClass">Part-of-speech:</label>
          <select name="wordClass" id="wordClass" class="form-control col-3">      
            {$optionHtml}
          </select>
      </div>
HTML;
    return $html;
  }

  private function _writeSenseCategories()
  {
    $categories = SenseCategories::getAllUnusedCategories($this->_slip->getAutoId(),
      $_REQUEST["headword"], $this->_slip->getWordClass());
    $dropdownHtml = '<option value="">-- select a category --</option>';
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
        <div class="senseCategoriesContainer">
          <h5>Sense Categories</h5> 
          <div class="form-group row">
            <div class="col-md-3">
                  <label for="senseCategorySelect">Choose existing sense category:</label>
            </div>
            <div>
                <select id="senseCategorySelect">{$dropdownHtml}</select>  
            </div>
            <div class="col-md-1">
                  <button type="button" class="form-control btn btn-success" id="chooseSenseCategory">Add</button>
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
                  <button type="button" class="form-control btn btn-success" id="addSenseCategory">Add</button>
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
    $handler = new XmlFileHandler($this->_slip->getFilename());
    $preScope = $this->_slip->getPreContextScope();
    $postScope = $this->_slip->getPostContextScope();
    $context = $handler->getContext($this->_slip->getId(), $preScope, $postScope);
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
    $contextHtml .= '<span id="slipWordInContext">' . $context["word"] . '</span>';
    if ($context["post"]["startJoin"] != "left" && $context["post"]["startJoin"] != "both") {
      $contextHtml .= ' ';
    }
    $contextHtml .= $context["post"]["output"];
    echo <<<HTML
            <div id="slipContextContainer">
              <h4>Adjust citation context</h4>
              <div>
                <span><a href="#" class="updateContext btn-link" id="decrementPre">-</a></span>
                <span><a {$preHref} class="updateContext" id="incrementPre">+</a></span>
              </div>
              <span data-precontextscope="{$preScope}" data-postcontextscope="{$postScope}" id="slipContext">
                {$contextHtml}
              </span>
              <div>
                <span><a href="#" class="updateContext btn-link" id="decrementPost">-</a></span>
                <span><a href="#" class="updateContext" id="incrementPost">+</a></span>
              </div>
            </div>
HTML;
  }

  private function _writeJavascript() {
    echo <<<HTML
        <script>    
            $("#chooseSenseCategory").on('click', function () {
              var elem = $( "#senseCategorySelect option:selected" );
              var category = elem.text();
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
              if($(this).val() == "verb") {
                $('#verbSelects').show();
                $('#nonVerbalNounOptions').show();
                $('#nounSelects').hide();
              } else if ($(this).val() == "noun") {
                $('#nounSelects').show();
                $('#verbSelects').hide();
              } else {
                $('#nounSelects').hide();
                $('#verbSelects').hide();
              }
            });
            
            $('#posStatus').on('change', function() {
              if($(this).val() == "verbal noun") {
                $('#nonVerbalNounOptions').hide();
              } else {
                $('#nonVerbalNounOptions').show();
              }
            });
        </script>
HTML;
  }
}
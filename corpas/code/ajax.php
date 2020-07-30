<?php

require_once 'includes/include.php';

switch ($_REQUEST["action"]) {
  case "getContext":
    $handler = new XmlFileHandler($_GET["filename"]);
    $context = $handler->getContext($_GET["id"], $_GET["preScope"], $_GET["postScope"]);
    echo json_encode($context);
    break;
  case "loadSlip":
    $slip = new Slip($_GET["filename"], $_GET["id"], $_GET["auto_id"], $_GET["pos"], $_GET["preContextScope"], $_GET["postContextScope"]);
    $slip->updateResults($_GET["index"]); //ensure that "view slip" (and not "create slip") displays
    $filenameElems = explode('_', $slip->getFilename());
    $textId = $filenameElems[0];
    $results = array("auto_id"=>$slip->getAutoId(), "starred"=>$slip->getStarred(), "translation"=>$slip->getTranslation(),
      "notes"=>$slip->getNotes(), "preContextScope"=>$slip->getPreContextScope(),
      "postContextScope"=>$slip->getPostContextScope(), "wordClass"=>$slip->getWordClass(),
      "lastUpdated"=>$slip->getLastUpdated(), "textId"=>$textId, "slipMorph"=>$slip->getSlipMorph()->getProps());
    //code required for modal slips
    $handler = new XmlFileHandler($_GET["filename"]);
    $context = $handler->getContext($_GET["id"], $results["preContextScope"], $results["postContextScope"]);
    $results["context"] = $context;
    //
    echo json_encode($results);
    break;
  case "saveSlip":
    $slip = new Slip($_POST["filename"], $_POST["id"], $_POST["starred"], $_POST["translation"],
      $_POST["notes"], $_POST["preContextScope"], $_POST["postContextScope"], $_POST["wordClass"]);
    unset($_POST["action"]);
    $slip->saveSlip($_POST);
    echo "success"; //TODO: remove or replace with something more useful ...
    break;
  case "getDictionaryResults":
    $locs = $_POST["locs"];
    $locations = explode('|', $locs);
    $filename = "";
    $fileHandler = null;
    $results = array();
    foreach ($locations as $location) {
      $elems = explode(' ', $location);
      if ($filename != $elems[0]) {
        $filename = $elems[0];
        $fileHandler = new XmlFileHandler($filename);
      }
      $context = $fileHandler->getContext($elems[1], 8, 8);
      $context["date"] = $elems[2];   //return the date of language
      $context["auto_id"] = $elems[3]; //return the auto_id (slip id)
      $context["title"] = str_replace("\\", " ", $elems[4]);   //return the title
      $context["page"] = $elems[5]; //return the page no
      $results[] = $context;
    }
    echo json_encode($results);
    break;
}

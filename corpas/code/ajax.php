<?php

require_once 'includes/include.php';

switch ($_REQUEST["action"]) {
  case "getContext":
    $handler = new XmlFileHandler($_GET["filename"]);
    $context = $handler->getContext($_GET["id"], $_GET["preScope"], $_GET["postScope"]);
    echo json_encode($context);
    break;
  case "loadSlip":
    $slip = new Slip($_GET["filename"], $_GET["id"]);
    if ($slip->getIsNew()) {
      echo json_encode(array("new"=>true));
      break;
    }
    $results = array("starred"=>$slip->getStarred(), "translation"=>$slip->getTranslation(),
      "notes"=>$slip->getNotes(), "preContextScope"=>$slip->getPreContextScope(),
      "postContextScope"=>$slip->getPostContextScope(), "lastUpdated"=>$slip->getLastUpdated());
    echo json_encode($results);
    break;
  case "saveSlip":
    $slip = new Slip($_POST["filename"], $_POST["id"]);
    unset($_POST["action"]);
    $slip->saveSlip($_POST);
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
      $results[] = $context;
    }
    echo json_encode($results);
    break;
}

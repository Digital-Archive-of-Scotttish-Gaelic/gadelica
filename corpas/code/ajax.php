<?php

require_once 'includes/include.php';

switch ($_GET["action"]) {
  case "getContext":
    $handler = new XmlFileHandler($_GET["filename"]);
    $context = $handler->getContext($_GET["id"], 20);
    echo json_encode($context);
    break;
}

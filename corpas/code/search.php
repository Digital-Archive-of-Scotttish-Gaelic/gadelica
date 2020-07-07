<?php

require_once "includes/htmlHeader.php";

$origin = $_GET["origin"] ? $_GET["origin"] : "search.php";   //originating script for back link

$controller = new SearchController($origin);

require_once "includes/htmlFooter.php";

/*

MM documentation
----------------

Input parameters –

action = newSearch

action = runSearch
  & search = craobh
  & mode = headword | wordform
  & view = corpus | dictionary
  & date = off | random | asc | desc
  & selecteddates = | 1900-1999
  & submit =

  & pp = 10
  & page = 2 | 3 | ...
  & case = | sensitive
  & accent = | sensitive
  & lenition = | sensitive
  & hits = 39

*/

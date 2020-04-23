<?php

//constants
define(INPUT_FILEPATH, "./CorpasXMLLemmatiser/outputFiles/");

/* autoload classes anonymous function */
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});

<?php

//constants

//test dirs
define('TXT_FILEPATH', "txtFiles/");
define('XML_FILEPATH', "xmlFiles/");

//define('INPUT_FILEPATH', "../../editableTXT/");
//define('OUTPUT_FILEPATH', "../../outXML/");

/* autoload classes anonymus function */
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});

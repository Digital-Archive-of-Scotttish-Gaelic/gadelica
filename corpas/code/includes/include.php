<?php

//constants
define(INPUT_FILEPATH, "./CorpasXMLLemmatiser/outputFiles/");

define(DB, "corpas");
define(DB_HOST, "130.209.99.241");
define(DB_USER, "corpas");
define(DB_PASSWORD, "XmlCraobh2020");

/* autoload classes anonymous function */
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});

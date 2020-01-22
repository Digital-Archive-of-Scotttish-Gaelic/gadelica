<?php

//constants
define(INPUT_FILEPATH, "./inputFiles/");
define(OUTPUT_FILEPATH, "./outputFiles/");
//DB
define(DB_HOST, "localhost");
define(DB_NAME, "multidict");
define(DB_USER, "web");
define(DB_PASSWORD, "craobh");

//DB_HOST . ";dbname=" . $dbName . ";charset=utf8;", DB_USER, DB_PASSWORD

/* autoload classes anonymus function */
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});

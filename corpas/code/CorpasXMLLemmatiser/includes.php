<?php

//constants
define(INPUT_FILEPATH, "./inputFiles/");
define(OUTPUT_FILEPATH, "./outputFiles/");

/* autoload classes anonymus function */
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});

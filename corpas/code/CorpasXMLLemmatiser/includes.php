<?php

//constants
//define(INPUT_FILEPATH, "./inputFiles/");
define(INPUT_FILEPATH, "./inputFiles/");
define(OUTPUT_FILEPATH, "../../xml/");
//DB
define(DB_HOST, "localhost");
define(DB_NAME, "multidict");
define(DB_USER, "web");
define(DB_PASSWORD, "craobh");

define(SKIP_EXISTING_FILES, true);

//DB_HOST . ";dbname=" . $dbName . ";charset=utf8;", DB_USER, DB_PASSWORD

/* autoload classes anonymus function */
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});


/* stop execution on warning */
function errHandle($errNo, $errStr, $errFile, $errLine) {
  $msg = "$errStr in $errFile on line $errLine";
  if ($errNo == E_NOTICE || $errNo == E_WARNING) {
    throw new ErrorException($msg, $errNo);
  } else {
    echo $msg;
  }
}
set_error_handler('errHandle');
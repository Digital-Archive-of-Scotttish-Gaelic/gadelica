<?php

session_start();

//constants
define("INPUT_FILEPATH", "../xml/");

define("DB", "corpas");
define("DB_HOST", "130.209.99.241");
define("DB_USER", "corpas");
define("DB_PASSWORD", "XmlCraobh2020");

/* autoload classes anonymous function */
spl_autoload_register(function ($class) {
  include 'classes/' . $class . '.php';
});

function gdSort($s, $t) {
  $accentedvowels = array('à','è','ì','ò','ù','À','È','Ì','Ò','Ù','ê','ŷ','ŵ','â','á','é','í','ó','ú','Á','É','Í','Ó','Ú');
  $unaccentedvowels = array('a','e','i','o','u','A','E','I','O','U','e','y','w','a','a','e','i','o','u','A','E','I','O','U');
  $str3 = str_replace($accentedvowels,$unaccentedvowels,$s);
  $str4 = str_replace($accentedvowels,$unaccentedvowels,$t);
  return strcasecmp($str3,$str4);
}

?>

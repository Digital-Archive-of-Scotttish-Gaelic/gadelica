<?php

$input = file_get_contents('../txt/tmp.txt');

// punctuation
$input = preg_replace("/([.,;:?!’‘”“'\"\)\(-\/–])/u", "<pc>$1</pc>", $input);
$input = preg_replace("/\s<pc>(.)<\/pc>\s/u", " <pc join=\"no\">$1</pc> ", $input);
$input = preg_replace("/(\S)<pc>(.)<\/pc>(\S)/u", "$1 <pc join=\"both\">$2</pc> $3", $input);
$input = preg_replace("/\s<pc>(.)<\/pc>(\S)/u", " <pc join=\"right\">$1</pc> $2", $input);
$input = preg_replace("/<pc>(.)<\/pc>/u", " <pc join=\"left\">$1</pc> ", $input);

// whitespace
$input = "<p>" . $input;
$input = preg_replace("/(\[TD )(\d+)(\])/u", "<pb n=\"$2\"/>", $input);
$input = preg_replace("/\R\R/u", "</p><p>", $input);
$input = preg_replace("/\R/u", "<lb/>", $input);
$input = preg_replace("/<\/p><lb\/>/u", "</p>", $input);
$input = preg_replace("/(<p>)(<pb n=\"\d+\"\/>)(<\/p>)/u", "$2", $input);
$input = preg_replace("/<p>/u", "\n<p>\n", $input);
$input = preg_replace("/<\/p>/u", "\n</p>", $input);
$input = preg_replace("/<pb /u", "\n<pb ", $input);
$input = preg_replace("/<lb\/>/u", "\n<lb/>\n", $input);
$input = preg_replace("/(\S) <pc /u", "$1\n<pc ", $input);
$input = preg_replace("/<\/pc> (\S)/u", "</pc>\n$1", $input);
$input = preg_replace("/\s(\w+)\s/u", " <w>$1</w> ", $input);
$input = preg_replace("/\s(\w+)\s/u", " <w>$1</w> ", $input);
$input = preg_replace("/<w>/u", "\n<w>", $input);
$input = preg_replace("/> <pc /u", ">\n<pc ", $input);
$input = preg_replace("/(\S) </u", "$1\n<", $input);
$input = preg_replace("/ </u", "<", $input);
$input = preg_replace("/<w>(\w+)/u", "<w pos=\"\" lemma=\"$1\">$1", $input);

$input .= "\n";

echo $input;


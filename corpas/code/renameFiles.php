<?php

$dir = "/var/www/html/dasg.arts.gla.ac.uk/www/gadelica/corpas/code/tmp";
//$dir = "/Users/stephenbarrett/Sites/gadelica/corpas/code/tmp";

$filenames = scandir($dir);

foreach ($filenames as $name) {
	if (mb_substr($name, 0, 1) != '.') {
		//rename files
/*		$newName = $dir . "/" . "test_" . $name;
		rename($dir . "/" . $name, $newName);
*/
		//remove prefix
/*		$prefix = "test_";
		$newName =  $dir . "/" . str_replace($prefix, "", $name);
		rename($dir . "/" . $name, $newName);
*/
	}
}

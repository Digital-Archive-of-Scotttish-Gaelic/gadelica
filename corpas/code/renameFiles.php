
<?php

$dir = "/var/www/html/dasg.arts.gla.ac.uk/www/gadelica/corpas/scans/400_Gairm.bk";
//$dir = "/Users/stephenbarrett/Sites/gadelica/corpas/code/tmp";

renameFiles($dir);

function renameFiles($rootDir)
{
	$directories = scandir($rootDir);
	foreach ($directories as $dir) {
		if (is_dir($dir)) {
			if (mb_substr($dir, 0, 1) != '.') {
				$dir = $rootDir . "/" . $dir;
				$filenames = scandir($dir);
				foreach ($filenames as $file) {

					//rename files
					/*
						$newName = $dir . "/" . str_replace(' ', '_',  $file);
						rename($dir . "/" . $file, $newName);
					*/
					//remove prefix
					/*
						$prefix = "400_Gairm_";
						$newName =  $dir . "/" . str_replace($prefix, "", $file);
						rename($dir . "/" . $file, $newName);
					*/
				}
			}
		}
	}
}
<?php
/* converts the corpus into a csv file for import to lemma database */

namespace models;
require_once "../includes/include.php";
require_once "../models/database.php";

$titles = array();
$media = array();
$dates = array();

//get all the text info
$db = new database();
$sql = <<<SQL
		SELECT id, filepath, title, date, partOf FROM text
SQL;
$results = $db->fetch($sql);

//iterate through each text
foreach ($results as $result) {
	$filepath = $result["filepath"];
	$title = $result["title"];
	$date = $result["date"];
	$partOf = $result["partOf"];
	if (!$filepath && !$date && !$partOf) { //skip
		continue;
	}
	//populate the dates array
	if ($date && $filepath) {
		$dates[$filepath] = $date;
	} else if ($partOf) {
		$dates[$filepath] = getParentDate($partOf);
	}
	//populate the titles array
	if ($partOf) {
		$titles[$filepath] = getParentTitle($title, $partOf);
	} else {
		$titles[$filepath] = $title;
	}
}

/**
 * Recursive function to assemble a title string based on a text title's ancestor(s)
 * @param string $title the title of this subtext
 * @param int $parentId the ID of its parent text
 * @return string the formatted title
 */
function getParentTitle($title, $parentId) {
	$title = $title;
	global $db;
	$sql = <<<SQL
		SELECT partOf, title FROM text WHERE id = :id
SQL;
	$results = $db->fetch($sql, array(":id"=>$parentId));
	$result = $results[0];
	$parentTitle = $result["title"];
	$partOf = $result["partOf"];
	$title = $parentTitle . " – " . $title;
	if ($partOf) {
		$title = getParentTitle($title, $partOf);
	}
	return $title;
}

/**
 * Recursive function to get the date for a text from its ancestor(s)
 * @param int $id the parent ID
 * @return string the date
 */
function getParentDate($parentId) {
	global $db;
	$sql = <<<SQL
		SELECT filepath, partOf, date FROM text WHERE id = :id
SQL;
	$results = $db->fetch($sql, array(":id"=>$parentId));
	$result = $results[0];
	$filepath = $result["filepath"];
	$date = $result["date"];
	$partOf = $result["partOf"];

	if (!$partOf && !$filepath && !$date) {
		return "";
	}

	if ($date) {
		return $date;
	} else {
		if ($date = getParentDate($partOf)) {
			return $date;
		}
	}
}

//iterate through the XML files and get the lemmas, etc
$path = '/var/www/html/dasg.arts.gla.ac.uk/www/gadelica/corpas/xml';
if (getcwd()=='/Users/stephenbarrett/Sites/gadelica/corpas/code/mm_utilities') {
	$path = '../../xml';
}
$it = new \RecursiveDirectoryIterator($path);
foreach (new \RecursiveIteratorIterator($it) as $nextFile) {
	if ($nextFile->getExtension()=='xml') {
		$xml = simplexml_load_file($nextFile);
		$xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
		foreach ($xml->xpath("//dasg:w") as $nextWord) {
			$lemma = (string)$nextWord['lemma'];
			if ($lemma) { echo $lemma . ','; }
			else { echo $nextWord . ','; }
			if (getcwd()=='/Users/stephenbarrett/Sites/gadelica/corpas/code/mm_utilities') {
				$filename = substr($nextFile,10);
			} else {
				$filename = substr($nextFile,58);
			}
			echo $filename . ',';
			echo $nextWord['id'] . ',';
			echo $nextWord . ',';
			echo $nextWord . ',';
			echo $nextWord['pos'] . ',';
			if ($dates[$filename]) { echo $dates[$filename] . ','; }
			else { echo '9999,'; }
			if ($titles[$filename]) { echo '"' . $titles[$filename] . '",'; }
			else { echo '6666,'; }
			$nextWord->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
			$pageNum = $nextWord->xpath("preceding::dasg:pb[1]/@n");
			echo $pageNum[0] . ",";
			if ($media[$filename]) { echo $media[$filename]; }
			else { echo '7777'; }
			echo PHP_EOL;
		}
	}
}

?>

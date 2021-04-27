<?php

require_once 'includes/include.php';

$db = new \models\database();

//get the current slip data for group 2
$sql =  <<<SQL
	SELECT auto_id, group_id, lemma AS headword, wordClass AS wordclass FROM slips s 
		JOIN lemmas l ON s.id = l.id AND s.filename = l.filename 
		WHERE group_id = 2
SQL;
$slips = $db->fetch($sql);

//create the entries
foreach ($slips as $slip) {
	$sql = <<<SQL
		SELECT id FROM entry WHERE headword = :headword AND wordclass = :wordlcass
SQL;
	$results = $db->fetch($sql, array(":headword"=>$slip["headword"], ":wordclass"=>$slip["wordclass"]));
	$entryId = null;
	if (empty($results[0])) {
		$sql = <<<SQL
			INSERT INTO entry (group_id, headword, wordclass) VALUES (:groupId, :headword, :wordclass)
SQL;
		$db->exec($sql, array(":groupId" => $slip["group_id"], ":headword" => $slip["headword"], ":wordclass" => $slip["wordclass"]));
		$entryId = $db->getLastInsertId();
	} else {
		$entryId = $results[0]["id"];
	}
	$sql = <<<SQL
		UPDATE slips SET entry_id = :entryId WHERE auto_id = :slipId
SQL;
	$sql->exec($sql, array(":entryId"=>$entryId, ":slipId"=>$slip["auto_id"]));
}


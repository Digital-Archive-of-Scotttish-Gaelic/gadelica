<?php

namespace models;

class entries
{
	public static function getEntry($lemma, $wordclass) {
		$entry = new entry($lemma, $wordclass);
//		$entry = self::_getWordforms($entry);
		$entry = self::_getSenses($entry);
		return $entry;
	}

	private static function _getWordforms($entry) {
		$db = new database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
        SELECT wordform, auto_id FROM lemmas l
            JOIN slips s ON s.filename = l.filename AND s.id = l.id
            WHERE s.group_id = {$_SESSION["groupId"]} AND lemma = :lemma AND wordclass = :wordclass
            ORDER BY wordform ASC
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":lemma"=>$entry->getLemma(), ":wordclass"=>$entry->getWordclass()));
			while ($row = $sth->fetch()) {
				$wordform = mb_strtolower($row["wordform"], "UTF-8");  //make all forms lowercase and ensure Unicode
				$slipId = $row["auto_id"];
				$entry->addForm($wordform, $slipId);
				$slipMorphResults = collection::getSlipMorphBySlipId($slipId);
				$entry->addSlipMorphString($wordform, $slipId, implode(' ', $slipMorphResults));
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		return $entry;
	}

	/**
	 * Queries DB for sense data and adds sense objects, indexed by slip ID, to entry object
	 * @param $entry entry object
	 * @return entry object
	 */
	private static function _getSenses($entry) {
		$db = new database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
				SELECT se.id as id, auto_id AS slipId FROM sense se
					JOIN slip_sense ss ON ss.sense_id = se.id
					JOIN slips s ON s.auto_id = ss.slip_id
        	WHERE s.group_id = {$_SESSION["groupId"]} AND  se.headword = :lemma AND se.wordclass = :wordclass
            ORDER BY name ASC
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":lemma"=>$entry->getLemma(), ":wordclass"=>$entry->getWordclass()));
			while ($row = $sth->fetch()) {
				$sense = new sense($row["id"]);
				$slipId = $row["slipId"];
				$entry->addSense($sense, $slipId);
			} } catch (PDOException $e) {
			echo $e->getMessage();
		}
		return $entry;
	}

  public static function getAllEntries() {
    $entries = array();
    $db = new database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sql = <<<SQL
        SELECT DISTINCT lemma, wordclass FROM lemmas l
            JOIN slips s ON s.filename = l.filename AND s.id = l.id
            WHERE s.group_id = {$_SESSION["groupId"]}
            ORDER BY lemma ASC
SQL;
      $sth = $dbh->prepare($sql);
      $sth->execute();
      while ($row = $sth->fetch()) {
        $entries[] = $row;
      }
      return $entries;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }
}
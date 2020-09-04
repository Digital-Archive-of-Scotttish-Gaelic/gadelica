<?php


class Entries
{
	public static function getEntry($lemma, $wordclass) {
		$entry = new Entry($lemma, $wordclass);
		$entry = self::_getWordforms($entry);
		$entry = self::_getSenses($entry);
		return $entry;
	}

	private static function _getWordforms($entry) {
		$db = new Database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
        SELECT wordform, auto_id FROM lemmas l
            JOIN slips s ON s.filename = l.filename AND s.id = l.id
            WHERE lemma = :lemma AND wordclass = :wordclass
            ORDER BY wordform ASC
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":lemma"=>$entry->getLemma(), ":wordclass"=>$entry->getWordclass()));
			while ($row = $sth->fetch()) {
				$wordform = mb_strtolower($row["wordform"], "UTF-8");  //make all forms lowercase and ensure Unicode
				$slipId = $row["auto_id"];
				$entry->addForm($wordform, $slipId);
				$slipMorphResults = Slips::getSlipMorphBySlipId($slipId);
				$entry->addSlipMorphString($wordform, $slipId, implode(' ', $slipMorphResults));
			}
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
		return $entry;
	}

	private static function _getSenses($entry) {
		$db = new Database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
        SELECT category, auto_id FROM senseCategory sc
        JOIN slips s ON s.auto_id = sc.slip_id
        JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
        WHERE lemma = :lemma AND wordclass = :wordclass
            ORDER BY category ASC
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":lemma"=>$entry->getLemma(), ":wordclass"=>$entry->getWordclass()));
			while ($row = $sth->fetch()) {
				$sense = $row["category"];
				$slipId = $row["auto_id"];
				$entry->addSense($sense, $slipId);
			} } catch (PDOException $e) {
			echo $e->getMessage();
		}
		return $entry;
	}

  public static function getAllEntries() {
    $entries = array();
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sql = <<<SQL
        SELECT DISTINCT lemma, wordclass FROM lemmas l
            JOIN slips s ON s.filename = l.filename AND s.id = l.id
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
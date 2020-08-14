<?php


class Entries
{
	public static function getEntry($lemma, $wordclass) {
		$entry = new Entry($lemma, $wordclass);
		$entry = self::_getWordforms($entry);
		$entry->setSenses(SenseCategories::getAllUsedCategories($lemma, $wordclass));
		foreach ($entry->getSenses() as $sense) {
			$entry->addSenseSlipData($sense, Slips::getSlipsBySenseCategory($lemma, $wordclass, $sense));
		}
		return $entry;
	}

	private static function _getWordforms($entry) {
		$db = new Database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
        SELECT DISTINCT wordform FROM lemmas l
            JOIN slips s ON s.filename = l.filename AND s.id = l.id
            WHERE lemma = :lemma AND wordclass = :wordclass
            ORDER BY lemma ASC
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":lemma"=>$entry->getLemma(), ":wordclass"=>$entry->getWordclass()));
			while ($row = $sth->fetch()) {
				$entry->addForm($row["wordform"]);
				$slipData = Slips::getSlipsByWordform($entry->getLemma(), $entry->getWordclass(), $row["wordform"]);
				$entry->addFormSlipData($row["wordform"], $slipData);
				$slipMorphData = Slips::getSlipMorphByWordform($entry->getLemma(), $entry->getWordclass(), $row["wordform"]);
				$entry->addSlipMorphData($row["wordform"], $slipMorphData);
			}
		} catch (PDOException $e) {
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
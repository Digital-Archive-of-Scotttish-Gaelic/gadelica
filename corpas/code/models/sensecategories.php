<?php

namespace models;

class sensecategories
{
	/**
	 * Adds a new sense entry to the database and returns its ID
	 * @param $name
	 * @param $description
	 * @param $headword
	 * @param $wordclass
	 * @return string : the ID of the newly created sense
	 */
  public static function addSense($name, $description, $headword, $wordclass) {
    $db = new database();
    $dbh = $db->getDatabaseHandle();
    $sql = <<<SQL
			INSERT INTO sense(name, description, headword, wordclass)
				VALUES (:name, :description, :headword, :wordclass)
SQL;
    try {
      $sth = $dbh->prepare($sql);
      $sth->execute(array(":name"=>$name, ":description"=>$description, ":headword"=>$headword, ":wordclass"=>$wordclass));
      return $db->getLastInsertId();
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

	/**
	 * Deletes a sense from the database and removes all its associated slip references also
	 * @param $id : the sense ID
	 */
  public static function deleteSense($id) {
    $db = new database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sth = $dbh->prepare("DELETE FROM sense WHERE id = :id");
      $sth->execute(array(":id" => $id));
      $sth2 = $dbh->prepare("DELETE FROM slip_sense WHERE sense_id = :id");
      $sth2->execute(array(":id" => $id));
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

	/**
	 * Adds a record to the slip_sense table matching a slip to a sense
	 * @param $slipId
	 * @param $senseId
	 */
  public static function saveSlipSense($slipId, $senseId) {
	  $db = new database();
	  $dbh = $db->getDatabaseHandle();
	  try {
		  $sth = $dbh->prepare("INSERT INTO slip_sense VALUES(:slipId, :senseId)");
		  $sth->execute(array(":slipId" => $slipId, ":senseId" => $senseId));
	  } catch (PDOException $e) {
		  echo $e->getMessage();
	  }
  }

	/**
	 * Removes a record in the slip_sense table
	 * @param $slipId
	 * @param $senseId
	 */
	public static function deleteSlipSense($slipId, $senseId) {
		$db = new database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sth = $dbh->prepare("DELETE FROM slip_sense WHERE slip_id = :slipId AND sense_id = :senseId");
			$sth->execute(array(":slipId" => $slipId, ":senseId" => $senseId));
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * Updates a sense record
	 * @param $id
	 * @param $name
	 * @param $description
	 */
	public static function updateSense($id, $name, $description) {
		$db = new database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
				UPDATE sense SET name = :name, description = :description WHERE id = :id
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":id" => $id, ":name" => $name, ":description" => $description));
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}



	/**
	 * Fetches all the categories used for a given lemma/wordclass combination
	 * @param $slipId
	 * @return array
	 */
/*	public static function getAllUsedCategories($lemma, $wordclass) {
		$categories = array();
		$db = new database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
        SELECT DISTINCT category FROM senseCategory sc
        	JOIN slips s ON s.auto_id = sc.slip_id 
        	JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
        	WHERE s.group_id = {$_SESSION["groupId"]} AND lemma = :lemma AND wordclass = :wordclass
            ORDER BY category ASC
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":lemma"=>$lemma, ":wordclass"=>$wordclass));
			while ($row = $sth->fetch()) {
				$categories[] = $row["category"];
			}
			return $categories;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}*/

	/**
	 * Fetches all the slipIds without a sense for a given lemma/wordclass combination
	 * @param $lemma
	 * @param $wordclass
	 * @return array of slipIds
	 */
	public static function getNonCategorisedSlipIds($lemma, $wordclass) {
		$slipIds = array();
		$db = new database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
        SELECT auto_id FROM slips s 
        	JOIN lemmas l ON s.filename = l.filename AND s.id = l.id 
        	WHERE auto_id NOT IN (SELECT slip_id FROM slip_sense) AND lemma = :lemma AND wordclass= :wordclass 
        	AND group_id = {$_SESSION["groupId"]}
        	ORDER by auto_id ASC
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":lemma"=>$lemma, ":wordclass"=>$wordclass));
			while ($row = $sth->fetch()) {
				$slipIds[] = $row["auto_id"];
			}
			return $slipIds;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	public static function writeSenseModal() {
		echo <<<HTML
			<div class="modal fade" id="senseModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Sense</h5>
              </div>
              <div class="modal-body">
								<div class="form-group row">							
	                <label class="col-sm-3" for="newSenseName">Name:</label>
	                <input class="col=sm-7" type="text" size="40" id="modalSenseName" name="modalSenseName">
                </div>
                <div class="form-group row">
                  <label class="col-sm-3" for="modalSenseDescription">Description:</label>
                  <textarea class="col-sm-8" id="modalSenseDescription" name="modalSenseDescription" cols="100" rows="6">                  
                  </textarea>
                </div>
                <div id="modalSlipRemoveSection" class="form-group row">
                  <label class="col-sm-3" for="modalSenseSlipRemove">Remove from §<span id="modalSlipIdDisplay"></span></label>
                  <input type="checkbox" id="modalSenseSlipRemove" name="modalSenseSlipRemove">
                  <input type="hidden" id="modalSlipId" name="modalSlipId">
								</div>
                <input type="hidden" name="senseId" id="senseId">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                <button type="button" id="editSense" class="btn btn-primary">save</button>
              </div>
            </div>
          </div>
        </div>
HTML;
	}
}
<?php


class Slips
{
  /**
   * Get the slip info required for a browse table from the DB
   *
   * @return array of DB results
   */
  public static function getAllSlipInfo() {
    $slipInfo = array();
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sql = <<<SQL
        SELECT s.filename as filename, s.id as id, auto_id, pos, lemma, wordform, firstname, lastname,
                date_of_lang, title, page,
                s.wordclass as wordclass, l.pos as pos, s.lastUpdated as lastUpdated, category as senseCat,
                sm.relation as relation, sm.value as value
            FROM slips s
            JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
            LEFT JOIN senseCategory sc ON sc.slip_id = auto_id
            LEFT JOIN slipMorph sm ON sm.slip_id = auto_id
            LEFT JOIN user u ON u.email = s.updatedBy
            ORDER BY auto_id ASC
SQL;
      $sth = $dbh->prepare($sql);
      $sth->execute();
      while ($row = $sth->fetch()) {
        $slipId = $row["auto_id"];
        if (!isset($slipInfo[$slipId])) {
          $slipInfo[$slipId] = $row;
          $slipInfo[$slipId]["category"] = array();
          $slipInfo[$slipId]["category"][] = $row["senseCat"];
	        $slipInfo[$slipId]["relation"] = array();
	        $slipInfo[$slipId]["relation"][] = $row["value"];
        } else {
          $slipInfo[$slipId]["category"][] = $row["senseCat"];
	        $slipInfo[$slipId]["relation"][] = $row["value"];
        }
        //get the context uri
        $file = new XmlFileHandler($row["filename"]);
        $slipInfo[$slipId]["uri"] = $file->getUri();
      }
      return $slipInfo;
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

	/**
	 * Gets slip info form the DB to populate an Entry with data required for citations
	 * @param $lemma
	 * @param $wordclass
	 * @param $wordform
	 * @return array of DB results
	 */
  public static function getSlipsByWordform($lemma, $wordclass, $wordform) {
	  $slipInfo = array();
	  $db = new Database();
	  $dbh = $db->getDatabaseHandle();
	  try {
		  $sql = <<<SQL
        SELECT s.filename as filename, s.id as id, auto_id, pos, lemma, preContextScope, postContextScope,
                translation, date_of_lang, title, page
            FROM slips s
            JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
            WHERE lemma = :lemma AND wordclass = :wordclass AND wordform = :wordform
            ORDER BY auto_id ASC
SQL;
		  $sth = $dbh->prepare($sql);
		  $sth->execute(array(":lemma"=>$lemma, ":wordclass"=>$wordclass, ":wordform"=>$wordform));
		  while ($row = $sth->fetch()) {
			  $slipInfo[] = $row;
		  }
		  return $slipInfo;
	  } catch (PDOException $e) {
		  echo $e->getMessage();
	  }
  }

	/**
	 * Gets slip info from the DB to populate an Entry with data required for citations
	 * @param $lemma
	 * @param $wordclass
	 * @param $wordform
	 * @return array of DB results
	 */
	public static function getSlipMorphByWordform($lemma, $wordclass, $wordform) {
		$morphInfo = array();
		$db = new Database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
        SELECT relation, value
            FROM slipMorph sm
            JOIN slips s ON sm.slip_id = auto_id
        		JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
            WHERE lemma = :lemma AND wordclass = :wordclass AND wordform = :wordform
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":lemma"=>$lemma, ":wordclass"=>$wordclass, ":wordform"=>$wordform));
			while ($row = $sth->fetch()) {
				$morphInfo[] = $row;
			}
			return $morphInfo;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * Gets slip info from the DB to populate an Entry with data required for citations
	 * @param $lemma
	 * @param $wordclass
	 * @param $category : the sense category
	 * @return array of DB results
	 */
	public static function getSlipsBySenseCategory($lemma, $wordclass, $category) {
		$slipInfo = array();
		$db = new Database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
        SELECT s.filename as filename, s.id as id, auto_id, pos, lemma, preContextScope, postContextScope,
                translation, wordform, date_of_lang, title, page
            FROM slips s
            JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
            JOIN senseCategory sc on sc.slip_id = auto_id
            WHERE lemma = :lemma AND wordclass = :wordclass AND sc.category = :category 
            ORDER BY auto_id ASC
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":lemma"=>$lemma, ":wordclass"=>$wordclass, ":category"=>$category));
			while ($row = $sth->fetch()) {
				$slipInfo[] = $row;
			}
			return $slipInfo;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

  /**
   * Updates user and date columns
   */
  public static function touchSlip($slipId) {
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
      $sql = <<<SQL
          UPDATE slips SET updatedBy = :user, lastUpdated = now() WHERE auto_id = :slipId
SQL;
      $sth = $dbh->prepare($sql);
      $sth->execute(array(":user"=>$_SESSION["user"], ":slipId"=>$slipId));
    } catch (PDOException $e) {
      echo $e->getMessage();
    }
  }

  public static function writeSlipDiv() {
    echo <<<HTML
        <div id="slip">
            <div id="slipHeader">
              <div id="slipTopRight">
                  <div id="slipChecked">
                  <!--label for="slipStarred">Starred: </label>
                  <input type="checkbox" name="slipStarred" id="slipStarred"-->
                  </div>
                  <div id="slipNumber"></div>
              </div>
              <div id="slipHeadwordContainer">
                <span id="slipHeadword"></span>
                <span id="slipWordClass"></span>
              </div>
              <div id="slipTextNum"></div>
            </div>  <!-- end slipHeader -->

            <div id="slipBody">
              <!--div>
                <span><a href="#" class="updateContext btn-link" id="decrementPre">-</a></span>
                <span><a href="#" class="updateContext" id="incrementPre">+</a></span>
              </div-->

              <span data-precontextscope="20" data-postcontextscope="20" id="slipContext"></span>

              <!--div>
                <span><a href="#" class="updateContext btn-link" id="decrementPost">-</a></span>
                <span><a href="#" class="updateContext" id="incrementPost">+</a></span>
              </div-->
              <div id="slipTranslation"></div>
            </div>
            <div id="slipFooter">
                <div id="slipTextRefContainer">
                    <div id="slipTextRef"></div>
                </div>
                <div id="slipDate"></div>
            </div>
            <input type="hidden" id="slipFilename">
            <input type="hidden" id="slipId">
            <input type="hidden" id="auto_id">
            <input type="hidden" id="slipPOS">

        <!--
            <div>
                <label for="slipNotes">Notes:</label><br>
                <div id="slipNotes"></div>
            </div>
        -->
            <div style="text-align: right">
                <button type="button" id="editSlip" class="btn btn-primary">edit</button>
                <a id="closeSlipLink" href="#">close</a>
            </div>
        </div>

       <!-- added by MM -->
        <div class="modal fade" id="slipModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <span class="text-muted" style="float:right;" id="slipNo">§</span>
              </div>
              <div class="modal-body">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                <button type="button" id="editSlip" class="btn btn-primary">edit</button>
              </div>
            </div>
          </div>
        </div>

HTML;
  }
}
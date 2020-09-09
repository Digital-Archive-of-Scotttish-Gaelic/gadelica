<?php


class Slips
{
  /**
   * Get the slip info required for a browse table from the DB
   *
   * @return array of DB results
   */
  public static function getAllSlipInfo($offset = 0, $limit = 10, $search = "", $sort, $order) {
  	$sort = $sort ? $sort : "auto_id";
  	$order = $order ? $order : "ASC";
  	$params = array(":limit" => (int)$limit, ":offset" => (int)$offset);
    $db = new Database();
    $dbh = $db->getDatabaseHandle();
    try {
			$whereClause = "";
			if (mb_strlen($search) > 1) {     //there is a search to run
				$sth = $dbh->prepare("SET @search = :search");  //set a MySQL variable for the searchterm
				$sth->execute(array(":search" => "%{$search}%"));
				$whereClause = <<<SQL
					WHERE auto_id LIKE @search	
            	OR lemma LIKE @search
            	OR wordform LIKE @search
            	OR wordclass LIKE @search
            	OR lemma LIKE @search
            	OR firstname LIKE @search
            	OR lastname LIKE @search
SQL;
			}
	    $dbh->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
	    $sql = <<<SQL
        SELECT SQL_CALC_FOUND_ROWS s.filename as filename, s.id as id, auto_id, pos, lemma, wordform, firstname, lastname,
                date_of_lang, title, page, CONCAT(firstname, ' ', lastname) as fullname, locked,
                s.wordclass as wordclass, l.pos as pos, s.lastUpdated as lastUpdated, updatedBy
            FROM slips s
            JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
            LEFT JOIN user u ON u.email = s.ownedBy
            {$whereClause}
            ORDER BY {$sort} {$order}
            LIMIT :limit OFFSET :offset;
SQL;
			$sth = $dbh->prepare($sql);
      $sth->execute($params);
      $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
      $hits = $db->fetch("SELECT FOUND_ROWS() as hits;");
      foreach ($rows as $index => $slip) {
      	$slipId = $slip["auto_id"];
      	//get the categories
	      $sql = <<<SQL
					SELECT category as senseCat
						FROM senseCategory sc
						LEFT JOIN slips s ON sc.slip_id = auto_id
						WHERE slip_id = :slipId
SQL;
	      $catRows = $db->fetch($sql, array(":slipId" => $slipId));
	      foreach ($catRows as $cat) {
	      	$rows[$index]["categories"] .= '<span class="badge badge-success">' . $cat["senseCat"] . '</span> ';
	      }

	      //get the senses
	      $sql = <<<SQL
					SELECT value
						FROM slipMorph sm
						LEFT JOIN slips s ON sm.slip_id = auto_id
						WHERE slip_id = :slipId
SQL;
	      $senseRows = $db->fetch($sql, array(":slipId" => $slipId));
	      foreach ($senseRows as $sense) {
		      $rows[$index]["senses"] .= '<span class="badge badge-secondary">' . $sense["value"] . '</span> ';
	      }
	      $checked = in_array($slipId, $_SESSION["printSlips"]) ? "checked" : "";
				$rows[$index]["printSlip"] = <<<HTML
					<input type="checkbox" class="chooseSlip" {$checked} id="printSlip_{$slipId}"> 
HTML;

      	//create the slip link code
	      $slipUrl = <<<HTML
                <a href="#" class="slipLink2"
                    data-toggle="modal" data-target="#slipModal"
                    data-auto_id="{$slip["auto_id"]}"
                    data-headword="{$slip["lemma"]}"
                    data-pos="{$slip["pos"]}"
                    data-id="{$slip["id"]}"
                    data-xml="{$slip["filename"]}"
                    data-uri="{$slip["uri"]}"
                    data-date="{$slip["date_of_lang"]}"
                    data-title="{$slip["title"]}"
                    data-page="{$slip["page"]}"
                    data-resultindex="-1"
                    title="view slip {$slip["auto_id"]}">
                    {$slip["auto_id"]}
                </a>
HTML;
	      $rows[$index]["auto_id"] = $slipUrl;
      }
      return array("total"=>(int)$hits[0]["hits"], "totalNotFiltered"=>count($rows), "rows"=>$rows);
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
 /* public static function getSlipsByWordform($lemma, $wordclass, $wordform) {
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
  }*/

	/**
	 * Gets slip info from the DB
	 * @param $slipId
	 * @return array of DB results
	 */
	public static function getSlipInfoBySlipId($slipId) {
		$slipInfo = array();
		$db = new Database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
        SELECT s.filename as filename, s.id as id, auto_id, pos, lemma, preContextScope, postContextScope,
                translation, date_of_lang, title, page, starred
            FROM slips s
            JOIN lemmas l ON s.filename = l.filename AND s.id = l.id
            WHERE s.auto_id = :slipId
            ORDER BY auto_id ASC
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":slipId"=>$slipId));
			while ($row = $sth->fetch()) {
				$slipInfo[] = $row;
			}
			return $slipInfo;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}

	/**
	 * Gets morph info from the DB to populate an Entry with data required for citations
	 * @param $slipId
	 * @return array of DB results
	 */
	public static function getSlipMorphBySlipId($slipId) {
		$morphInfo = array();
		$db = new Database();
		$dbh = $db->getDatabaseHandle();
		try {
			$sql = <<<SQL
        SELECT relation, value
        	FROM slipMorph
        	WHERE slip_id = :slipId
SQL;
			$sth = $dbh->prepare($sql);
			$sth->execute(array(":slipId"=>$slipId));
			while ($row = $sth->fetch()) {
				$morphInfo[$row["relation"]] = $row["value"];
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
	 * @param $wordform
	 * @return array of DB results
	 */
/*	public static function getSlipMorphByWordform($lemma, $wordclass, $wordform) {
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
				$morphInfo[$row["relation"]] = $row["value"];
			}
			return $morphInfo;
		} catch (PDOException $e) {
			echo $e->getMessage();
		}
	}*/

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
	 * Sends an email to slip owner to request a slip unlock
	 * @param $slipId
	 * @param $ownerEmail
	 */
	public static function requestUnlock($slipId, $ownerEmail) {
		$owner = Users::getUser($ownerEmail);
		$user = Users::getUser($_SESSION["user"]);
		$slip = self::getSlipInfoBySlipId($slipId)[0];
		$editUrl = "https://dasg.ac.uk/gadelica/corpas/code/slipEdit.php";
		$editUrl .= <<<HTML
			?{$slip["filename"]}&id={$slip["id"]}&headword={$slip["lemma"]}&pos={$slip["pos"]}&auto_id={$slipId}
HTML;
		$emailText = <<<HTML
			<p>Dear {$owner->getFirstName()},</p>
			<p>{$user->getFirstName()} {$user->getLastName()} has requested that slip #{$slipId} be unlocked.</p>
			<p>You can view and update the slip <a href="{$editUrl}">here</a></p>
			<p>If you have received this email in error or have any other queries please contact <a title="Email DASG" href="mailto:mail@dasg.ac.uk">mail@dasg.ac.uk</a>.</p>	
			<p>Kind regards</p>
			<p>The DASG team</p>
HTML;
		$email = new Email($ownerEmail, "Slip Unlock Request", $emailText, "mail@dasg.ac.uk");
		$email->send();
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
                <a id="lockedBtn" data-toggle="tooltip" data-owner="" data-slipid="" title="Slip is locked - click to request unlock" class="d-none lockBtn locked btn btn-large btn-danger" href="#">
                  <i class="fa fa-lock" aria-hidden="true"></i></a>
                <a data-toggle="tooltip" title="Slip is unlocked" class="d-none lockBtn unlocked btn btn-large btn-success" href="#">
                  <i class="fa fa-unlock" aria-hidden="true"></i></a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">close</button>
                <button type="button" id="editSlip" class="btn btn-primary">edit</button>
              </div>
            </div>
          </div>
        </div>

HTML;
  }
}
<?php

namespace models;

class CorpusSearch
{
	private $_db;

	public function __construct() {
		$this->_db = $this->_db ? $this->_db : new Database();
	}

	/**
	 * Takes an array of database results and searches through the XML corpus for matches
	 * @param $dbResults: the database result set
	 * @return array
	 */
	public function getFileSearchResults($dbResults) {
		$fileResults = array();
		$i = 0;
		foreach ($dbResults as $result) {
			$id = $result["id"];
			$fileResults[$i]["id"] = $id;
			$fileResults[$i]["lemma"] = $result["lemma"];
			$fileResults[$i]["pos"] = $result["pos"];
			$fileResults[$i]["date_of_lang"] = $result["date_of_lang"];
			$fileResults[$i]["filename"] = $result["filename"];
			$fileResults[$i]["auto_id"] = $result["auto_id"];
			$fileResults[$i]["title"] = $result["title"];
			$fileResults[$i]["page"] = $result["page"];
			$i++;
		}
		return $fileResults;
	}

	/**
	 * Form and return the query required for a wordform search
	 * @param $params: the parameters required for the query
	 * @return array: ("sql" => the SQL, "search" => the search term)
	 */
	private function _getWordformQuery($params) {
		$search = $params["search"];
		$searchPrefix = "[[:<:]]";  //default to word boundary at start
		if ($params["accent"] != "sensitive") {
			$search = Functions::getAccentInsensitive($search, $params["case"] == "sensitive");
		}
		if ($params["lenition"] != "sensitive") {
			$search = Functions::getLenited($search);
			$search = Functions::addMutations($search);
		} else {
			//deal with h-, n-, t-
			$searchPrefix = "^";  //don't use word boundary at start of search, but start of string instead
		}
		$whereClause = "";
		$search = $searchPrefix . $search . "[[:>:]]";  //word boundary
		if ($params["case"] == "sensitive") {   //case sensitive
			$whereClause .= "wordform_bin REGEXP ?";
		} else {                              //case insensitive
			$whereClause .= "wordform REGEXP ?";
		}
		$selectFields =  "lemma, l.filename AS filename, l.id AS id, wordform, pos, date_of_lang, title, page, medium, s.auto_id AS auto_id";
		$sql = <<<SQL
        SELECT SQL_CALC_FOUND_ROWS  {$selectFields} FROM lemmas AS l
          LEFT JOIN slips s ON l.filename = s.filename AND l.id = s.id AND group_id = {$_SESSION["groupId"]}
          WHERE {$whereClause}
SQL;
		return array("sql" => $sql, "search" => $search);
	}

	/**
	 * Runs the query to get the corpus database result set
	 * @param $params: the array of parameters for the query
	 * @return array ("hits" => number of hits, "results" => the result set)
	 */
	public function getDBSearchResults($params) {
		$perpage = $params["pp"];
		$pagenum = $params["page"];
		$offset = $pagenum == 1 ? 0 : ($perpage * $pagenum) - $perpage;

		switch ($params["date"]) {
			case "random":
				$orderBy = "RAND()";
				break;
			case "asc":
				$orderBy = "date_of_lang ASC";
				break;
			case "desc":
				$orderBy = "date_of_lang DESC";
				break;
			default:
				$orderBy = "filename, id";
		}
		if ($params["mode"] == "headword") {    //lemma
			$query["search"] = $params["search"];
			$query["sql"] = <<<SQL
        SELECT SQL_CALC_FOUND_ROWS l.filename AS filename, l.id AS id, wordform, pos, lemma, date_of_lang, title, 
                page, medium, s.auto_id as auto_id, s.wordClass as wordClass 
            FROM lemmas AS l
            LEFT JOIN slips s ON l.filename = s.filename AND l.id = s.id AND group_id = {$_SESSION["groupId"]}
            WHERE lemma = ?
           
SQL;
		} else {                               //wordform
			$query = $this->_getWordformQuery($params);
		}
		if ($params["selectedDates"]) {       //restrict by date
			$query["sql"] .= $this->_getDateWhereClause($params);
		}
		$query["sql"] .= $this->_getMediumWhereClause($params); //restrict by medium
		if ($params["pos"][0] != "") {
			$query["sql"] .= $this->_getPOSWhereClause($params);  //restrict by POS
		}
		if ($params["filename"]) {    //restrict to single file
			$query["sql"] .= " AND l.filename = '{$params["filename"]}' ";
		} else if ($params["uri"]) {  //restrict to all files in this text
			$parts = explode("_", $params["uri"]);
			$query["sql"] .= " AND l.filename LIKE('{$parts[1]}\_%') ";
		}
		$query["sql"] .= <<<SQL
        ORDER BY {$orderBy}
SQL;
		$query["sql"] .= <<<SQL
				LIMIT {$perpage} OFFSET {$offset}
SQL;

		$results = $this->_db->fetch($query["sql"], array($query["search"]));
		$hits = $this->_db->fetch("SELECT FOUND_ROWS() as hits;");
		return array("results" => $results, "hits" => $hits[0]["hits"]);
	}

	private function _getDateWhereClause($params) {
		$dates = explode('-', $params["selectedDates"]);
		$whereClause = " AND date_of_lang >= {$dates[0]} AND date_of_lang <= {$dates[1]} ";
		return $whereClause;
	}

	private function _getMediumWhereClause($params) {
		$whereClause = "";
		if (!$params["medium"] || count($params["medium"]) == 3) {
			return $whereClause;    //don't bother with restrictions if all selected
		}
		$whereClause = " AND (";
		foreach ($params["medium"] as $medium) {
			$mediumString[] = " medium = '{$medium}' ";
		}
		$whereClause .= implode(" OR ", $mediumString);
		$whereClause .= ") ";
		return $whereClause;
	}

	private function _getPOSWhereClause($params) {
		$whereClause = " AND (";
		foreach ($params["pos"] as $pos) {
			$posString[] = " BINARY pos REGEXP '{$pos}\$|{$pos}[[:space:]]' ";
		}
		$whereClause .= implode(" OR ", $posString);
		$whereClause .= ") ";
		return $whereClause;
	}

	//Retrieves the minimum and maximum dates of language in the database
	public static function getMinMaxDates() {
		$db = new Database();
		$sql = <<<SQL
        SELECT MIN(date_of_lang) AS min, MAX(date_of_lang) AS max FROM lemmas
            WHERE date_of_lang != ''
SQL;
		$result = $db->fetch($sql, array());
		return $result[0];
	}

	/**
	 * Retrieves a list of distinct parts of speech
	 * @return array of distinct POS strings
	 */
	public static function getDistinctPOS() {
		$db = new Database();
		$sql = <<<SQL
        SELECT DISTINCT BINARY pos FROM lemmas 
            ORDER BY pos
SQL;
		$results = $db->fetch($sql);
		//parse out the extra POS info
		$distinctPOS = array();
		foreach ($results as $result) {
			if (in_array($result[0], $distinctPOS) || stristr($result[0], " ") || $result[0] == "") {
				continue;
			}
			$distinctPOS[] = $result[0];
		}
		return $distinctPOS;
	}
}
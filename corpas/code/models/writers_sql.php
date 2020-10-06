<?php


namespace models;


class writers_sql
{
	public static function getWriters() {
		$writers = array();
		$db = new database();
		$sql = <<<SQL
			SELECT id, surname_gd FROM writer ORDER by surname_gd ASC
SQL;
		$results = $db->fetch($sql);
		foreach ($results as $result) {
			$writers[] = new writer_sql($result["id"]);
		}
		return $writers;
	}
}
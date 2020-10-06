<?php

namespace controllers;
use models, views;

class writer_sql
{
	public function run($action) {
		if (empty($action)) {
			$action = "list";
		}
		switch ($action) {
			case "view":
				$writer = new models\writer_sql($_GET["writerId"]);
				$view = new views\writer_sql();
				$view->printWriter($writer);
				break;
			case "list":
				$writers = models\writers_sql::getWriters();
				$view = new views\writer_sql();
				$view->listWriters($writers);
				break;
		}
	}
}
<?php


namespace controllers;
use views, models;

class issue
{
	public function run($action) {

		switch ($action) {
			case "browse":
				$view = new views\issue();
				$view->show();
				break;
			case "edit":
				$issue = new models\issue($_GET["id"]);
				$view = new views\issue($issue);
				$view->show("edit");
				break;
		}
	}
}
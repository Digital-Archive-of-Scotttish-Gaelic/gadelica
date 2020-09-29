<?php

namespace controllers;
use views;

require_once "includes/include.php";

class PrintSlip
{
	public function __construct() {
		$action = $_GET["action"] ? $_GET["action"] : "print";
		switch ($action) {
			case "print":
				$slipIds = array_keys($_SESSION["printSlips"]);
				//reset the array
				$_SESSION["printSlips"] = array() ;
				$view = new views\PrintSlip();
				$view->write($slipIds);
				break;
		}
	}

}
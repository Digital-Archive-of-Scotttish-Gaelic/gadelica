<?php

require_once "includes/include.php";

class PrintSlipController
{
	public function __construct() {
		$action = $_GET["action"] ? $_GET["action"] : "print";
		switch ($action) {
			case "print":
				$slipIds = array_keys($_SESSION["printSlips"]);
				//reset the array
				$_SESSION["printSlips"] = array() ;
				$view = new PrintSlipView();
				$view->write($slipIds);
				break;
		}
	}

}
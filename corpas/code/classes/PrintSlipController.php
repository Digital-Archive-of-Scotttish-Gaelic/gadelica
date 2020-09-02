<?php

require_once "includes/include.php";

class PrintSlipController
{
	public function __construct() {
		$action = $_REQUEST["action"] ? $_REQUEST["action"] : "print";
		switch ($action) {
			case "print":
				$slipIds = array_keys($_REQUEST["printSlips"]);
				$view = new PrintSlipView();
				$view->write($slipIds);
				break;
		}
	}

}
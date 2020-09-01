<?php

require_once "includes/include.php";

class PrintSlipController
{
	public function __construct() {
		$action = $_REQUEST["action"] ? $_REQUEST["action"] : "print";
		switch ($action) {
			case "print":
				$slipIds = array(908, 912, 877, 875, 598, 735);
				$view = new PrintSlipView();
				$view->write($slipIds);
				break;
		}
	}

}
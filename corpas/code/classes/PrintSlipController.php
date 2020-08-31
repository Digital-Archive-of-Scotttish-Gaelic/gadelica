<?php


class PrintSlipController
{
	public function __construct() {
		$action = $_REQUEST["action"] ? $_REQUEST["action"] : "print";
		switch ($action) {
			case "print":
				$view = new PrintSlipView();
				$view->write();
				break;
		}
	}

}
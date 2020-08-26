<?php


class Entry
{
	private $_lemma, $_wordclass, $_slipMorphString;
	private $_forms = array();
	private $_formSlipData = array();
	private $_senses = array();
	private $_senseSlipData = array();
	private $_slipMorphData = array();
	private $_formSlipIds = array();

	public function __construct($lemma, $wordclass) {
		$this->_lemma = $lemma;
		$this->_wordclass = $wordclass;
	}

	//Getters

	public function getLemma() {
		return $this->_lemma;
	}

	public function getWordclass() {
		return $this->_wordclass;
	}

	public function getForms() {
		return $this->_forms;
	}

	public function getFormSlipData($form) {
		return $this->_formSlipData[$form];
	}

	public function getSenses() {
		return $this->_senses;
	}

	public function getSenseSlipData($sense) {
		return $this->_senseSlipData[$sense];
	}

	public function getSlipMorphData($form) {
		return $this->_slipMorphData[$form];
	}

	public function getSlipMorphString($form, $slipId) {
		return $this->_slipMorphString[$form][$slipId];
	}

	public function getSlipMorphValues($form) {
		$values = array();
		foreach($this->getSlipMorphData($form) as $value) {
			$values[] = $value;
		}
		return $values;
	}

	public function getFormSlipIds($form) {
		return $this->_formSlipIds[$form];
	}

	public function getUniqueForms() {
		$forms = array();
		foreach ($this->getForms() as $slipId => $form) {
			$forms[] = $form . "|" . $this->getSlipMorphString($form, $slipId);
		}
		return array_unique($forms);
	}

	//Setters

	public function setForms($forms) {
		$this->_forms = $forms;
	}

	public function addForm($slipId, $form) {
		$this->_forms[$slipId] = $form;
		$this->_formSlipIds[$form][] = $slipId;
	}

	public function addFormSlipData($form, $data) {
		$this->_formSlipData[$form] = $data;
	}

	public function setSenses($senses) {
		$this->_senses = $senses;
	}

	public function addSenseSlipData($sense, $data) {
		$this->_senseSlipData[$sense] = $data;
	}

	public function addSense($sense) {
		$this->_senses[] = $sense;
	}

	public function setSlipMorphData($form, $data) {
		$this->_slipMorphData[$form] = $data;
	}

	public function addSlipMorphString($form, $slipId, $string) {
		$this->_slipMorphString[$form][$slipId] = $string;
	}
}
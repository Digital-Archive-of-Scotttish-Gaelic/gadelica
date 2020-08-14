<?php


class Entry
{
	private $_lemma, $_wordclass;
	private $_forms = array();
	private $_formSlipData = array();
	private $_senses = array();
	private $_senseSlipData = array();
	private $_slipMorphData = array();

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

	public function getSlipMorphValues($form) {
		$values = array();
		foreach($this->getSlipMorphData($form) as $key => $value) {
			$values[] = $value;
		}
		return array_unique($values);
	}

	//Setters

	public function setForms($forms) {
		$this->_forms = $forms;
	}

	public function addForm($form) {
		$this->_forms[] = $form;
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
}
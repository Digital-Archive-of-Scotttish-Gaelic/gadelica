<?php


namespace models;


class entry
{
	private $_id, $_groupId, $_headword, $_wordclass, $_notes, $_updated;

	public function __construct($id) {
		$this->_id = $id;
	}

	//SETTERS

	public function setGroupId($groupId) {
		$this->_groupId = $groupId;
	}

	public function setHeadword($headword) {
		$this->_headword = $headword;
	}

	public function setWordclass($wordclass) {
		$this->_wordclass = $wordclass;
	}

	public function setNotes($notes) {
		$this->_notes = $notes;
	}

	public function setUpdated($timestamp) {
		$this->_updated = $timestamp;
	}

	// GETTERS

	public function getId() {
		return $this->_id;
	}

	public function getGroupId() {
		return $this->_groupId;
	}

	public function getHeadword() {
		return $this->_headword;
	}

	public function getWordclass() {
		return $this->_wordclass;
	}

	public function getNotes() {
		return $this->_notes;
	}

	public function getUpdated() {
		return $this->_updated;
	}


}
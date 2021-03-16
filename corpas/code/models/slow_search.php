<?php


namespace models;


class slow_search
{
	private $_filepathOffset = 58;  //used to find the filename in a path
	private $_path = '/var/www/html/dasg.arts.gla.ac.uk/www/gadelica/corpas/xml'; //server path to XML files

	public function __construct() {
		if (substr(getcwd(), 0, 6) == "/Users") {   //for local testing
			$this->_filepathOffset = 7;
			$this->_path = "../xml";
		}
	}

	public function search($xpath) {
		$results = array();
		$it = new \RecursiveDirectoryIterator($this->_path);
		$i = 0;
		foreach (new \RecursiveIteratorIterator($it) as $nextFile) {
			if ($nextFile->getExtension() == 'xml') {
				$filename = substr($nextFile, $this->_filepathOffset);
				$handler = new xmlfilehandler($filename);
				$xml = simplexml_load_file($nextFile);
				$xml->registerXPathNamespace('dasg', 'https://dasg.ac.uk/corpus/');
				$result  = $xml->xpath($xpath);
				foreach ($result as $id) {
					$results[$i]["data"] = corpus_search::getDataById($filename, $id);

					$results[$i]["data"]["key"] = $it->key();
					$results[$i]["context"] = $handler->getContext($id);

					if ($i == 2) {return $results;}
					$i++;
				}
			}
		}
		return $results;
	}
}
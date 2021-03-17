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

	public function search($xpath, $offsetFilename="", $offsetId="") {
		$results = array();
		$it = new \RecursiveDirectoryIterator($this->_path);
		$i = 1;
		foreach (new \RecursiveIteratorIterator($it) as $nextFile) {
			if ($nextFile->getExtension() == 'xml') {
				$filename = substr($nextFile, $this->_filepathOffset);


				if ($offsetFilename && ($offsetFilename != $filename)) {
					continue;
				} else if ($offsetFilename == $filename) {
					$offsetFilename = "";
				}


				$handler = new xmlfilehandler($filename);
				$xml = simplexml_load_file($nextFile);
				$xml->registerXPathNamespace('dasg', 'https://dasg.ac.uk/corpus/');
				$result  = $xml->xpath($xpath);
				foreach ($result as $id) {

					if ($offsetId && $offsetId != $id) {
						continue;
					} else if ($offsetId == $id) {
						$offsetId = "";
						continue;
					}

					$results[$i]["data"] = corpus_search::getDataById($filename, $id);
					$results[$i]["data"]["context"] = $handler->getContext($id);
					$results[$i]["data"]["slipLinkHtml"] = collection::getSlipLinkHtml($results[$i]["data"]);
					$pos = new partofspeech($results[$i]["data"]["pos"]);
					$results[$i]["data"]["posLabel"] = $pos->getLabel();
					$results[$i]["count"] = $i;

					//limit to 6 results
					if ($i == 6) {return $results;}
					$i++;
				}
			}
		}
		return $results;
	}
}
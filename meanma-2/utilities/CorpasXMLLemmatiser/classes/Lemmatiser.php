<?php


class Lemmatiser
{
	private $_lexicon = [];
	private $_iterator;

	public function __construct() {
		$this->_iterator = new RecursiveDirectoryIterator(INPUT_FILEPATH);
	}

    private $filesToSkip = array('126.xml', '127.xml', '128-1.xml', '192.xml');     //currently invalid XML files to be fixed


    public function createLexicon() {
		$words = [];
		foreach (new RecursiveIteratorIterator($this->_iterator) as $nextFile) {
            if (in_array($nextFile->getFilename(), $this->filesToSkip)) {
                continue;
            }
			if ($nextFile->getExtension()=='xml') {
				$xml = simplexml_load_file($nextFile);

        echo "\n\n\n----- " . $nextFile->getFilename() . " -------\n\n\n";

            if ($xml != false) {
                $xml->registerXPathNamespace('dasg', 'https://dasg.ac.uk/corpus/');
                $status = isset($xml->xpath("/dasg:text/@status")[0]) ? $xml->xpath("/dasg:text/@status")[0] : '';
                if ($status == 'tagged') {
                    foreach ($xml->xpath("//dasg:w") as $nextWord) {
                        $form = $nextWord;
                        $lemma = (string)$nextWord['lemma'];
                        if ($lemma == '') {
                            $lemma = $form;
                        }
                        if (strtolower($lemma[0]) == $lemma[0]) {
                            $form = strtolower($form);
                        }
                        $pos = (string)$nextWord['pos'];
                        $words[] = $form . '|' . $lemma . '|' . $pos;
                    }
                }
            }


			}
		}
		usort($words,'gdSort');
		$lexicon = [];
		foreach ($words as $nextWord) {
			if ($lexicon[$nextWord]) {
				$lexicon[$nextWord]++;
			}
			else {
				$lexicon[$nextWord] = 1;
			}
		}

		foreach ($lexicon as $nextWord => $nextCount) {
			$bits = explode('|',$nextWord);
			if ($this->_lexicon[$bits[0]]) {
				$bits2 = explode('|',$this->_lexicon[$bits[0]]);
				if ($nextCount > $bits2[2]) {
					$this->_lexicon[$bits[0]] = $bits[1] . '|' . $bits[2] . '|' . $nextCount;
				}
			}
			else {
				$this->_lexicon[$bits[0]] = $bits[1] . '|' . $bits[2] . '|' . $nextCount;
			}
		}

		return $this->_lexicon;
	}

	public function tagFiles() {
		foreach (new RecursiveIteratorIterator($this->_iterator) as $nextFile) {
			if ($nextFile->getExtension()=='xml') {
                echo "\n\n\n--TAG--- " . $nextFile->getFilename() . " -------\n\n\n";

                if (in_array($nextFile->getFilename(), $this->filesToSkip)) {
                    continue;
                }
				$xml = simplexml_load_file($nextFile);
				$xml->registerXPathNamespace('dasg','https://dasg.ac.uk/corpus/');
				$status = $xml->xpath("/dasg:text/@status")[0];
				if ($status == 'raw') {
					foreach ($xml->xpath("//dasg:w") as $nextWord) {

                        //check the DB for a lemma
/*                        $db = DB::getDatabaseHandle();
                        $sql = "SELECT l.word AS lemma FROM lemma l JOIN form_modern f ON f.lemma_id = l.id WHERE f.word = '" . (string)$nextWord . "'";
                        $stmt = $db->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($result) {
                            echo "\nnextWord : " . (string)$nextWord . " - lemma : " . $result['lemma'];
                        } else {
                            echo "\nnextWord : " . (string)$nextWord . " - NOCHANGE : ";
                        }


                        //$nextWord["lemma"] = $result['lemma'];
*/
						if ($this->_lexicon[(string)$nextWord]) {
							$bits = explode('|',$this->_lexicon[(string)$nextWord]);
							$nextWord['lemma'] = $bits[0];
							if ($bits[1]!='') {
								$nextWord['pos'] = $bits[1];
							}
							else {
								$nextWord['pos'] = False;
							}
						}
						else if ($this->_lexicon[strtolower((string)$nextWord)]) {
							$bits = explode('|',$this->_lexicon[strtolower((string)$nextWord)]);
							$nextWord['lemma'] = $bits[0];
							if ($bits[1]!='') {
								$nextWord['pos'] = $bits[1];
							}
							else {
								$nextWord['pos'] = False;
							}
						}
						else if (substr((string)$nextWord,1,1)==='h') {
							$delenited = substr((string)$nextWord,0,1) . substr((string)$nextWord,2);
							if ($this->_lexicon[$delenited]) {
								$bits = explode('|',$this->_lexicon[$delenited]);
								$nextWord['lemma'] = $bits[0];
								if ($bits[1]!='') {
									$nextWord['pos'] = $bits[1];
								}
								else {
									$nextWord['pos'] = False;
								}
							}
						}

					}
					$xml->asXML($nextFile);
				}
			}
		}
	}
}


define("DB_NAME", "faclair_beag");
define("DB_USER", "uuhdeyw8qpwj5");
define("DB_PASSWORD", "phlykzvny39l");

class DB
{
    private static $databaseHandle;
    const ERROR_REPORTING = true;

    private static function connect($dbName, $user = DB_USER, $pass= DB_PASSWORD)
    {
        try {
            self::$databaseHandle = new PDO(
                "mysql:host=localhost;dbname=" . $dbName . ";charset=utf8;", $user, $pass
            );
        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }

    public static function getDatabaseHandle($dbName = DB_NAME, $user = DB_USER, $pass = DB_PASSWORD)
    {
        self::connect($dbName, $user, $pass);

        if (self::ERROR_REPORTING)
            self::$databaseHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        self::$databaseHandle->query("SET NAMES utf8");

        return self::$databaseHandle;
    }

    public static function getLastId($dbName, $tableName)
    {
        $dbh = self::getDatabaseHandle($dbName);
        $stmt = $dbh->prepare("SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '{$dbName}' AND TABLE_NAME   = '{$tableName}'");
        $stmt->execute();
        $lastId = $stmt->fetch(PDO::FETCH_NUM);
        $lastId = $lastId[0];
        return $lastId;
    }
}
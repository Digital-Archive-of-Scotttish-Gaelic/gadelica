<?php


class Lemmatiser
{
	private $_lexicon = [];
	private $_iterator;

	public function __construct() {
		$this->_iterator = new RecursiveDirectoryIterator(INPUT_FILEPATH);
	}

    private $filesToSkip = array();     //currently invalid XML files to be fixed


    public function createLexicon() {
	//	$words = [];


        $db = DB::getDatabaseHandle();
        $sql = <<<SQL
                            SELECT l.word AS lemma, l.id FROM lemma l 
                                JOIN form_tagged f ON f.lemma_id = l.id 
                                                   WHERE f.word = ? ORDER BY id DESC LIMIT 1
SQL;
        $stmt1 = $db->prepare($sql);

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

                        //check for non-alphabetic characters
                        if (!preg_match('/^\p{L}+$/u', $nextWord)) {
                            // $string contains only letters, including accents (Unicode)
                            echo "\n\n----- IGNORING " . $nextWord . " -------\n\n";
                            continue;
                        }

                        $wordform = strtolower($nextWord);
                        $lemma = (string)$nextWord['lemma'];

                        //check the DB for a lemma
                        $stmt1->execute(array($wordform));

                        $result = $stmt1->fetch(PDO::FETCH_ASSOC);
                        if ($result) {
                            echo "\nnextWord : " . (string)$nextWord . " - DBlemma : " . $result['lemma'] . " - xmlLemma : " . $lemma;
                        } else {
                            $db->beginTransaction();

                            try {
                                // Step 1: Check if the lemma already exists
                                $stmt = $db->prepare("SELECT id FROM lemma_xml WHERE word = :lemma_word");
                                $stmt->execute([':lemma_word' => $lemma]);
                                $lemma_id = $stmt->fetchColumn();

                                // Step 2: Insert the lemma if it doesn't exist
                                if (!$lemma_id) {
                                    $stmt = $db->prepare("INSERT INTO lemma_xml (word) VALUES (:lemma_word)");
                                    $stmt->execute([':lemma_word' => $lemma]);
                                    $lemma_id = $db->lastInsertId();
                                }

                                // Step 3: Check if the word + lemma_id already exists in form_tagged_xml
                                $stmt = $db->prepare("
        SELECT COUNT(*) FROM form_tagged_xml
        WHERE word = :form_word AND lemma_id = :lemma_id
    ");
                                $stmt->execute([
                                    ':form_word' => $wordform,
                                    ':lemma_id' => $lemma_id
                                ]);
                                $exists = $stmt->fetchColumn();

                                // Step 4: Insert only if it doesn't already exist
                                if (!$exists) {
                                    $stmt = $db->prepare("
            INSERT INTO form_tagged_xml (word, lemma_id)
            VALUES (:form_word, :lemma_id)
        ");
                                    $stmt->execute([
                                        ':form_word' => $wordform,
                                        ':lemma_id' => $lemma_id
                                    ]);
                                }

                                $db->commit();
                            } catch (Exception $e) {
                                $db->rollBack();
                                throw $e;
                            }
                        }

                        /*
                        $lemma = (string)$nextWord['lemma'];
                        if ($lemma == '') {
                            $lemma = $form;
                        }
                        if (strtolower($lemma[0]) == $lemma[0]) {
                            $form = strtolower($form);
                        }
                        $pos = (string)$nextWord['pos'];
                        $words[] = $form . '|' . $lemma . '|' . $pos;
                        */
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
        $db = DB::getDatabaseHandle();

        //query the Faclair Beag lemmas
        $sql = <<<SQL
                            SELECT l.word AS lemma FROM lemma l 
                                JOIN form_tagged f ON f.lemma_id = l.id 
                                                   WHERE f.word = ?
SQL;

        $stmt1 = $db->prepare($sql);

        //query the 'XML lemmas'
        $sql2 = <<<SQL
                            SELECT l.word AS lemma FROM lemma_xml l 
                                JOIN form_tagged_xml f ON f.lemma_id = l.id 
                                                   WHERE f.word = ?
SQL;
        $stmt2 = $db->prepare($sql2);

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

                        $lemma = $nextWord->lemma;

                        //check the DB for a lemma
                        $stmt1->execute(array($nextWord));
                        $result = $stmt1->fetch(PDO::FETCH_ASSOC);
                        if ($result) {
                            $lemma = $result['lemma'];
                            echo "\nnextWord : " . (string)$nextWord . " - lemma : " . $lemma;
                        } else {
                            $stmt2->execute(array($nextWord));

                            if ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                                $lemma = $result2['lemma'];
                                echo "\nnextWord : " . $nextWord . " - XML MATCH : " . $lemma;
                            } else { echo "\n NO MATCH in either DB for {$nextWord}\n}"; }
                        }

                        $nextWord["lemma"] = $lemma;
/*
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
*/

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
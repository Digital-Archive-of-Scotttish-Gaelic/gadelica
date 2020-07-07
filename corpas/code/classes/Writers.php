<?php


class Writers
{
  /**
   * Queries Fuseki for all writers and creates Writer objects
   * @return array of Writer objects
   */
  public static function getWriters() {
    $query = <<<SPQR
    SELECT DISTINCT ?writer
    WHERE
    {
      ?uri dc:creator ?writer .
      FILTER isURI(?writer) .
    }
SPQR;
    $spqr = new SPARQLQuery();
    $results = $spqr->getQueryResults($query);
    $writers = [];
    foreach ($results as $result) {
      $writers[] = new Writer($result->writer->value);
    }
    usort($writers, "self::cmp");
    return $writers;
  }

  /**
   * Sort array of Writer objects by surname
   * @param $a
   * @param $b
   * @return int|lt
   */
  private static function cmp($a, $b) {
    return strcmp($a->getSurname(), $b->getSurname());
  }
}
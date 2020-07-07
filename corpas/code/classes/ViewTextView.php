<?php


class ViewTextView
{
  private $_text;   //an instance of CorpusText

  public function __construct($text) {
    $this->_text = $text;
  }

  public function printText() {
    echo <<<HTML
        <h3>{$this->_text->getTitle()}</h3>
        <table class="table" id="meta" data-hi="{$this->_text->getId()}">
          <tbody>
            {$this->_getWritersHtml()}
            {$this->_getMediaHtml()}
            {$this->_getGenresHtml()}
            {$this->_getDateHtml()}
            {$this->_getPublisherHtml()}
            {$this->_getRatingHtml()}
            {$this->_getSuperURIHtml()}
            <tr><td>URI</td><td>{$this->_text->getURI()}</td></tr>
          </tbody>
        </table>
        <p>&nbsp;</p>
        {$this->_getSubURIHtml()}
        {$this->_text->getTransformedText()}
HTML;
    $this->_writeJavascript();
  }

  private function _getDateHtml() {
    if (!$this->_text->getDate()) {
      return "";
    }
    return "<tr><td>publication year</td><td>{$this->_text->getDate()}</td></tr>";
  }

  private function _getPublisherHtml() {
    if (!$this->_text->getPublisher()) {
      return "";
    }
    return "<tr><td>publisher</td><td>{$this->_text->getPublisher()}</td></tr>";
  }

  private function _getRatingHtml() {
    if (!$this->_text->getRating()) {
      return "";
    }
    return "<tr><td>rating</td><td>{$this->_text->getRating()}</td></tr>";
  }

  private function _getSuperURIHtml() {
    if (!$this->_text->getSuperURI()) {
      return "";
    }
    $html = '<tr><td>part of</td><td>';
    $html .= '<a href="viewText2.php?uri=' . $this->_text->getSuperURI() . '">';
    $html .= $this->_text->getSuperTitle();
    $html .= '</a></td></tr>';
    return $html;
  }

  private function _getWritersHtml() {
    if (!count($this->_text->getWriters())) {
      return "";
    }
    $html = '<tr><td>writer</td><td>';
    foreach ($this->_text->getWriters() as $nextWriter => $nextName) {
      if (substr($nextWriter,0,8)=='https://') {
        $html .= '<a href="viewWriter.php?uri=' . $nextWriter . '">';
        $html .= $nextName;
        $html .= '</a>';
      }
      else {
        $html .= $nextWriter;
      }
      if ($nextWriter !== end(array_keys($this->_text->getWriters()))) {
        $html .= ', ';
      }
    }
    $html .= '</td></tr>';
    return $html;
  }

  private function _getMediaHtml() {
    if (!count($this->_text->getMedia())) {
      return "";
    }
    $html = '<tr><td>medium</td><td>';
    foreach ($this->_text->getMedia() as $nextMedium) {
      $html .= '<a class="badge badge-primary" href="#">';
      $html .= $nextMedium;
      $html .= '</a> ';
    }
    $html .= '</td></tr>';
    return $html;
  }

  private function _getGenresHtml() {
    if (!count($this->_text->getGenres())) {
      return "";
    }
    $html = '<tr><td>genre</td><td>';
    foreach ($this->_text->getGenres() as $nextGenre) {
      $html .= '<a class="badge badge-primary" href="#">';
      $html .= $nextGenre;
      $html .= '</a> ';
    }
    $html .= '</td></tr>';
    return $html;
  }

  private function _getSubURIHtml() {
    if (!count($this->_text->getSubURIs())) {
      return "";
    }
    $html = '<div class="list-group list-group-flush">';
    foreach ($this->_text->getSubURIs() as $nextSubURI) {
      $html .= '<div class="list-group-item list-group-item-action">';
      $html .= '#' . $nextSubURI["rank"] . ': <a href="viewText2.php?uri=' . $nextSubURI["uri"] .'">' . $nextSubURI["title"];
      $html .= '</a></div>';
    }
    $html .= '</div>';
    return $html;
  }

  private function _writeJavascript() {
    echo <<<HTML
    <script>
      $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        hi = $('#meta').attr('data-hi');
        $('#'+hi).css('background-color', 'yellow');
        $('body').animate({scrollTop: $('#'+hi).offset().top - 180},500);
      });
    </script>
HTML;
  }
}
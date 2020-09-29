<?php

namespace views;

class Writer
{
  private $_writer;   //an instance of Writer

  /**
   * Create a list of all writers
   * @param $writers an array of Writer objects
   */
  public function listWriters($writers) {
    echo <<<HTML
        <p><a href="index.php">&lt; Back to corpus index</a></p>
        <h1>Corpas na Gàidhlig writers</h1>
        <table class="table">
            <tbody>
                {$this->_getWritersListHtml($writers)}
            </tbody>
        </table>
HTML;
  }

  private function _getWritersListHtml($writers) {
    $html = "";
    foreach ($writers as $writer) {
      $nickname = $writer->getNickname() ? "({$writer->getNickname()})" : "";
      $lifespan = $writer->getYearOfBirth() ? $writer->getYearOfBirth() . " - " . $writer->getYearOfDeath() : "";
      $html .= <<<HTML
        <tr>
            <td><a href="viewWriter.php?uri={$writer->getURI()}">
                {$writer->getForenames()} {$writer->getSurname()}</a> {$nickname}</td>
            </td>
            <td>{$lifespan}</td>
            <td><a href="#" class="badge badge-primary">{$writer->getOrigin()}</a></td>
        </tr>
HTML;
    }
    return $html;
  }

  /**
   * Write HTML output of writer properties
   * @param $writer instance of Writer
   */
  public function printWriter($writer) {
    $this->_writer = $writer;
    $nickname = $this->_writer->getNickname() ? "({$this->_writer->getNickname()})" : "";
    echo <<<HTML
        <div class="container-fluid" style="max-width: 800px; float: left;">
            <p><a href="index.php">&lt; Back to corpus index</a></p>
            <p><a href="writers.php">&lt; Back to writer index</a></p>
            <h3>{$this->_writer->getForenames()} {$this->_writer->getSurname()} {$nickname}</h3>
            <table class="table">
                <tbody>
                    {$this->_getLifespanHtml()}
                    {$this->_getOriginHtml()}
                    {$this->_getParentHtml()}
                    {$this->_getChildrenHtml()}
                </tbody>
            </table>
            {$this->_getWorksHtml()}
        </div>
HTML;
    $this->_writeJavascript();
  }

  private function _getLifespanHtml() {
    if (!$this->_writer->getYearOfBirth()) {
      return "";
    }
    return "<tr><td>lifespan</td><td>{$this->_writer->getYearOfBirth()} - {$this->_writer->getYearOfDeath()}</td></tr>";
  }

  private function _getOriginHtml() {
    if (!$this->_writer->getOrigin()) {
      return "";
    }
    return <<<HTML
        <tr><td>origin</td>
            <td><a class="badge badge-primary" href="#">{$this->_writer->getOrigin()}</a></td>
        </tr>
HTML;
  }

  private function _getParentHtml() {
    $parent = $this->_writer->getParent();
    if (empty($parent)) {
      return "";
    }
    $nickname = $parent["nickname"] ? "({$parent["nickname"]})" : "";
    return <<<HTML
        <tr><td>parents</td>
            <td><a href="viewWriter.php?uri={$parent["uri"]}">
                    {$parent["forenames"]} {$parent["surname"]} {$nickname}
                </a>
            </td>
        </tr>
HTML;
  }

  private function _getChildrenHtml() {
    $children = $this->_writer->getChildren();
    if (empty($children)) {
      return "";
    }
    $html = '<tr><td>children</td><td>';
    foreach ($children as $uri => $child) {
      $html .= '<a href="viewWriter.php?uri=' . $uri . '">';
      $nickname = $child["nickname"] ? "({$child["nickname"]})" : "";
      $html .= "{$child["forenames"]} {$child["surname"]} {$nickname}</a>";
      if ($child != end(array_keys($children))) { $html .= ', '; }
    }
    $html .= '</td></tr>';
    return $html;
  }

  private function _getWorksHtml() {
    if (empty($this->_writer->getWorks())) {
      return "";
    }
    $html = "";
    foreach ($this->_writer->getWorks() as $uri => $title) {
      $html .= '<a href="viewText.php?uri=' . $uri . '">' . $title . '</a><br/>';
    }
    return $html;
  }

  private function _writeJavascript() {
    echo <<<HTML
        <script>
          $(function () {
            $('[data-toggle="tooltip"]').tooltip()
          })
        </script>
HTML;
  }
}
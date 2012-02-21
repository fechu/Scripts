<?php

namespace SM\Regex;

class Search
{
  /**
   * Der Regex nach dem gesucht werden soll.
   * */
  protected $regex = "";
  
  /**
   * Der String in dem gesucht werden soll. 
  */
  protected $subject = "";  
  
  /**
   * Soll die Suche Case Sensitive geschehen?
   */
  public $caseSensitive = FALSE;  
  
  /**
   * Erstellt eine Suche mit einer Regular Expression
   * @param $regex Der Ausdruck der verwendet werden soll.
   * */
  public function __construct($regex, $subject)
  {
    $this->regex = $regex;
    $this->subject = $subject;
  }
  
  /**
   * @return Die Ergebnisse in einem Array.
  */
  public function run()
  {
    preg_match_all("/" . $this->regex . "/" .($this->caseSensitive ? "" : "i"), $this->subject, $results);
    return isset($results[0]) ? $results[0] : array();
  }
  
}
?>

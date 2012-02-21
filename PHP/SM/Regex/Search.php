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
   * Sollen neue Zeilen ignoriert werden.
   */
  public $ignoreNewline = FALSE;  
  
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
   * @param $includeCaptured Wenn ja, wird das direkte Resultat von preg_match_all zurŸckgegeben. Ansonsten nur die Matches.
  */
  public function run($includeCaptured = FALSE)
  {
    preg_match_all("/" . $this->regex . "/" .($this->caseSensitive ? "" : "i") . ($this->caseSensitive ? "" : "s"), $this->subject, $results);
    
    if ($includeCaptured === TRUE)
    {
      return $results;    
    }
    else
    {
      return isset($results[0]) ? $results[0] : array();
    }
  }
  
}
?>

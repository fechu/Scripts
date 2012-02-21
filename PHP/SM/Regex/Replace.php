<?php

namespace SM\Regex;

class Replace
{
  /**
   * Der Regex nach dem gesucht werden soll.
   * */
  protected $regex = "";

  /**
   *  Mit was ersetzt werden soll.
   */
  protected $replacement = "";
  
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
  public function __construct($regex, $replacement, $subject)
  {
    $this->regex = $regex;
    $this->subject = $subject;
    $this->replacement = $replacement;
  }
  
  /**
   * @return Die Ergebnisse in einem Array.
  */
  public function run()
  {
    return preg_replace("/" . $this->regex . "/" . ($this->caseSensitive ? "" : "i"), $this->replacement, $this->subject);
  }
  
}
?>

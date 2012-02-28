<?php
namespace SM\Regex;

/**
 * The base class for a regex. Contains a few properties to help organize Regex classes. 
 */
class Regex {
    
    /**
     * The regular expression
     */
    protected $regex = "";
    
    /**
     * The regular expression is used with this string.
     */
    protected $subject = "";
    
    /**
     * Is the case ignored in the search
     */
    public $caseSensitive = true;
    
    /**
     * If yes, the dot in a regex catches a newline character
     */
    public $ignoreNewline = false;
    
    /**
     * Other modifiers than case sensitive (i) and global (g).
     * Use the getter and setter to change this value.
     */
    protected $modifiers = "";
    
    /**
     * Creates a new instance
     * @param String 	regex 		The regex
     * @param String	$subject 	The subject
     */
    public function __construct($regex, $subject)
    {
	if (!is_string($regex) OR !is_string($subject))
	{
	    throw new \InvalidArgumentException("Regular Expressions and subject have to strings");
	}
	
	$this->regex = $regex;
	$this->subject = $subject;
    }
    
    /**
     * Sets the modifiers
     * @param String $modifiers The modifiers for the regex.
     */
    public function setModifiers($modifiers)
    {
	// Accept only strings
	if (!is_string($modifiers))
	{
	    throw new \InvalidArgumentException("Modifiers is supposed to be a string!");
	}
	$this->modifiers = $modifiers;
    }
    
    /**
     * Returns the modifiers
     * @return	String		The modifiers.
     * @param	Boolean	$all	If yes, the values from $caseSensitive and $ignoreNewline are also added to the string. 
     */
    public function getModifiers($all)
    {
	$modifiers = $this->modifiers;
	
	// Should we add all modifiers?
	if ($all == true)
	{	    
	    // Add the case sensitive modifier
	    if (strpos($this->modifiers, "i") === false && $this->caseSensitive === false)
	    {
		$modifiers .= "i";
	    }
	    
	    // Add the global search if ignore newline is true
	    if (strpos($this->modifiers, "g") === false && $this->ignoreNewline === true)
	    {
		$modifiers .= "g";
	    }
	}
	
	return $modifiers;
    }
    
}
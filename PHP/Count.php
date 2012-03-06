#!/usr/bin/php
<?php

include_once 'Autoload.php';

class Count extends SM\Script\Script {
    
    /**
     * Returns the help for the random function
     */
    protected function help()
    {
		$help ="
		Use this script to count everything in texts.
		
		Usage: ./Count.php [-t <text>]
		
		Options
		    -t	The text you want to use.
		    ";
		return $help;
    }
    
    
    /**
     * Defines the possible options
     */
    protected function getOptions()
    {
		$short_options = array(
	    	"t:" => false
		);
	
		return array($short_options, array());
    }
    
    /**
     * The script. Count things in the given text.
     */
    protected function script()
   	{
		// Get the text.
		$text = $this->options['t'];
		
		// Count the characters
		$chars = strlen($text);
		$this->write("Characters: " . $chars);	
		
		// Count the characters without whitespaces
		$plainText = preg_replace("/\s*/i", "", $text);
		$charsWithout = strlen($plainText);
		$this->write("Characters without whitespaces: " . $charsWithout);
		
		// Count the words
		$words = preg_match_all("/\w+/", $text, $matches);
		$this->write("Words: ". $words);
		
		// Count the lines
		$lines = substr_count($text, "\n") + 1;
		$this->write("Lines: " . $lines);
		
	
		
		$this->write("Found Characters:");
		foreach(count_chars($text, 1) as $i => $val)
		{
		    $this->write(chr($i) . ": " . $val);
		}
    }
}

// Run the scripts
$script = new Count();
$script->run();

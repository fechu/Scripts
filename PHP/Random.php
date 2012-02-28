#!/usr/bin/php
<?php

include 'Autoload.php';

class Random extends SM\Script\Script {
    
    /**
     * Returns the help for the random function
     */
    protected function help()
    {
	$help ="
	Use this script to generate random strings.
	
	Usage: ./Random.php -l <length> [-n <count>]
	
	Options
	    -l	Length of the returned strings
	    -n	Number of random strings
	    ";
	return $help;
    }
    
    /**
     * Defines the possible options
     */
    protected function getOptions()
    {
	$short_options = array(
	    "l:" => false,
	    "n:" => false
	);
	
	return array($short_options, array());
    }
    
    /**
     * The scripts. Writes out the required numbers
     */
    protected function script()
    {
	// Return a random string
	$length = isset($this->options['l']) ? $this->options['l'] : 10;
	$count = isset($this->options['n']) ? $this->options['n'] : 1;
  
	// All possible characters
	$characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
	// Return the length
	for($i = 0; $i < $count; $i++)
	{
	    // Shuffle the characters
	    $characters = str_shuffle($characters);
	    // Write the random string
	    $this->write(substr($characters, 0, $length));
	}
    }
}

// Run the scripts
$script = new Random();
$script->run();

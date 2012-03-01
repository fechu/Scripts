#!/usr/bin/php
<?php

include 'Autoload.php';

class Random extends SM\Script\Script {
    
    /**
     * The characters used for generating random strings.
     */
    static protected $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
    
    /**
     * The number of characters in $characters.
     * This is static becuase of performance reasons.
     */
    static protected $character_count = 63;
    
    /**
     * Stores the firstnames as soon as they are needed. 
     */
    protected $firstnames = null;
    protected $firstnames_count = 0;
    
    /**
     * Stores the surnames as soon as they are needed.
     */
    protected $surnames = null;
    protected $surnames_count = 0;
    
    /**
     * Returns the help for the random function
     */
    protected function help()
    {
	$help ="
	Use this script to generate random strings.
	
	Usage: ./Random.php [-t <type>] [-l <length>] [-n <count>]
	
	Options
	    -l	Length of the returned strings
	    -n	Number of random strings
	    -t	The type of data you want. Possible values are
		string:	Returns a random string
		name: 	Returns a random name
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
	    "n:" => false,
	    "t:" => array(
		"string",
		"name"
	    )
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
	$type = isset($this->options['t']) ? $this->options['t'] : "string";
	
	for($i = 0; $i < $count; $i++)
	{
	    switch ($type)
	    {
		case "string":
		{
		    // Generate a random string
		    $this->write($this->generateRandomString($length));
		    break;
		}
		case "name":
		{
		    // Generate a random name
		    $this->write($this->generateRandomName());
		    break;
	        }
	    }
	}
    }
    
    /**
     * @param 	String	$length The length of the random string
     * @return	String	A random string with the given length
     */
    protected function generateRandomString($length)
    {
	$random_string = "";
	
	// Loop through the length
	while( strlen($random_string) < $length)
	{
	    $char_index = rand(0, self::$character_count);

	    $random_string .= self::$characters[$char_index];
	}
	
	return $random_string;
    }
    
    /**
     * @return	String	A random name
     */
    protected function generateRandomName()
    {
	$firstnames = $this->getFirstnames();
	$firstname_index = rand(0, $this->firstnames_count);
	$firstname = $firstnames[$firstname_index];
	
	$surnames = $this->getSurnames();
	$surname_index = rand(0, $this->surnames_count);
	$surname = $surnames[$surname_index];
	
	return $firstname . " " . $surname;
    
    }
    
    /**
     * Returns the firstnames. If they are not loaded yet, they will be loaded.
     */
    protected function getFirstnames()
    {
	if ($this->firstnames === null)
	{
	    // Load the names from the file
	    $content = file_get_contents(__DIR__ . "/Resources/Firstname.csv");
	    $this->firstnames = explode("\n", $content);
	    $this->firstnames_count = count($this->firstnames) - 1;
	}
	return $this->firstnames;
    }

    /**
     * Returns the secondnames. If they are not loaded yet, they will be loaded.
     */
    protected function getSurnames()
    {
	if ($this->surnames === null)
	{
	    // Load the names from the file
	    $content = file_get_contents(__DIR__ . "/Resources/Surname.csv");
	    $this->surnames = explode("\n", $content);
	    $this->surnames_count = count($this->surnames) - 1;
	}
	return $this->surnames;
    }

}

// Run the scripts
$script = new Random();
$script->run();

#!/usr/bin/php
<?php

include 'Autoload.php';

class Hash extends SM\Script\Script {
    
    protected function getOptions()
    {
	$short_options = array(
	    "s:"	=> false,	// A string to hash
	    "f:"	=> false,	// The file to hash
	    "a:"	=> array(	// The algorithm I should use
		"md5",
		"sha512",
		"sha256",
	    )
	);
	
	return array($short_options, array());
    }
    
    protected function script()
    {
	if (isset($this->options['s']))
	{
	    // Hash the string
	    fwrite(STDOUT, $this->hashString($this->options['s']) . "\n");
	    return;
	}
	
	if (isset($this->options['f']))
	{
	    $file = $this->options['f'];
	    if (!file_exists($file))
	    {
		fwrite(STDOUT, "I could not find a file at " . $file);
		return;
	    }
	    
	    // Hash the file
	    fwrite(STDOUT, $this->hashFile($file). "\n");
	}
    }
    
    /**
     * Hashes the given string.
     * This method uses the algorithm specified in the a options or md5 as default
     * @return 	String	The hash of the given string.
     */
    protected function hashString($string)
    {
	$algorithm = isset($this->options['a']) ? $this->options['a'] : 'md5';
	
	return hash($algorithm, $string);
    }
    
    /**
     * Hashes the given file.
     * This method uses the algorithm specified in the a options or md5 as default
     * @return 	String	The hash of the given file.
     */
    protected function hashFile($path)
    {
	$algorithm = isset($this->options['a']) ? $this->options['a'] : 'md5';
	
	return hash_file($algorithm, $path);
    }
    
    protected function help()
    {
	$help =	"
	I can help you hashing strings or files with all sort of different algorithms.
	
	Usage:	./Hash.php [-s <string>][-f <path>][-a <algorithm>]
	
	Options:
	    -s	The string to hash
	    -f	The file to hash
	    -a	The algorithm I should use to hash. 
	    ";
	
	fwrite(STDOUT, $help . "\n");
    }
}

// Run the script
$script = new Hash();
$script->run();
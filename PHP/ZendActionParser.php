#!/usr/bin/php
<?php 

/**
 *
 * Das Skript kann eine PHP Datei oder einen ganzen Ordner mit PHP Dateien scannen. 
 * Es schreibt anschliessend in eine Textdatei alle Namen der Zend Controller Actions. 
 * Diese sind in den Files in der Form "public function irgendwasAction()" vorhanden. 
 *
 */

// Get the given path
$path = isset($argv[1]) ? $argv[1] : NULL;
if (substr($path, -1 ,1) != '/') 
{
	$path .= '/';
}

if ($path === NULL) 
{
	fwrite(STDOUT, "Es wurde kein Pfad übergeben.\n");
	exit();
}

// Check if its a file.
if(!file_exists($path)) 
{
	fwrite(STDOUT, "Die angegebene Datei existiert nicht.\n");
}

if (is_dir($path)) 
{
	$files = scandir($path);
	$files = array_filter($files, 'possibleFile');
		
	$allActions = array();
	foreach ($files as $file) {
		$actions = scanFile($path . $file);
		$allActions[$file] = $actions;
	}
	generateOutput($allActions);
	
}
else if (is_file($path) && possibleFile($path)) 
{
	// Process the file.
	$actions = scanFile($path);
	if (count($actions) > 0) {
		$basename = pathinfo($path, PATHINFO_FILENAME);
		generateOutput(array($basename => $actions));
	}
	else {
		fwrite(STDOUT, "No Actions found!");
	}
}


/**
 * Überprüft ob das file am übergebenen Pfad ein mögliches File zum scannen ist. 
 * @param String $path Der Pfad zum File.
 */
function possibleFile($path) 
{
	// Only files with extension php
	if (pathinfo($path, PATHINFO_EXTENSION) == "php") {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Scannt das File auf alle Actions und gibt einen Array mit den Namen der Actions zurück.
 * @param Der Pfad zur Datei die gescannt werden soll. 
 */ 
function scanFile($path)
{
	fwrite(STDOUT, "Scanning file: " . pathinfo($path, PATHINFO_BASENAME) . "\n");

	$handle = fopen($path, "r");
	$content = fread($handle, filesize($path));
	
	$regex = "/public function ([A-Za-z]*)Action\(\)/";
	preg_match_all($regex, $content, $matches);
	
	// Return only the captured things.
	if (count($matches) > 1) 
	{
		return $matches[1];
	}
	else 
	{
		return array();
	}
}

/**
 * Erstellt ein Textfile mit all den Actions. 
 * @param Array $actions Ein Array mit den Namen der Aktionen. Der Array kann multidimensional sein. Dann werden im File Abschnitte erstellt. 
 */
function generateOutput($actions)
{
	fwrite(STDOUT, "Generating output...\n");
	
	$filename = "ZendActionParserOutput.txt";
	$handle = fopen($filename, "w");
	
	// Check if the first object is an array. 
	foreach ($actions as $key => $fileActions) {
		fwrite($handle, "\n--- " . $key . " ---\n");
		foreach ($fileActions as $action) {
			fwrite($handle, $action . "\n");
		}
	}
	
	fwrite(STDOUT, "Wrote output to file ZendActionParserOutput.txt\n");
	fclose($handle);
}

?>
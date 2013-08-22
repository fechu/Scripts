#!/usr/bin/php
<?php

include_once 'Autoload.php';

class FolderToList extends SM\Script\Script
{
	
	/**
	 * Ignore the names contained in this array. 
	 */
	protected $ignore_names;
	
	/**
	 * The length of the input folder path.
	 * This value is used to truncate the the complete path.
	 */
	protected $input_folder_length;
	
	/**
	 * Returns the help for the FolderToList Script.
	 */
	protected function help()
	{
		$help = "
		This script reads the structure of a folder and generates a list out of it.
		
		Usage: ./FolderToList.php -i <Folder> [-f <format> ][--no-ext]
		
		Options
		    -i			The folder you want to create a list out of it. The name of this folder won't be included in the list.
		    -f			The format. Default is text. Options: csv, text
    		--no-ext	If specified, the resultung path will be printed without the file extension. 
		    ";
		return $help;
	}
	
	/**
	 * Initialises the array with the ignored files.
	 */
	protected function init()
	{
		$this->ignore_names = array(
			".", 
			"..", 
			".DS_Store"
		);
	}
	
	/**
	 * Defines the possible options
	 */
	protected function getOptions()
	{
		$short_options = array(
			"i:" => false, 
			"f:" => array("text", "csv")
		);
		
		$long_options = array(
			"no-ext" => false,
		);
		
		return array($short_options, $long_options);
	}
	
	/**
	 * The script. Goes through the folders and generates the list..
	 */
	protected function script()
	{
		// Get a list of all files in the folder
		$input_folder = $this->options['i'];
		$this->input_folder_length = strlen($input_folder);
		$folderlist = $this->parseFolder($input_folder);
		
		// Check if the given path was a folder
		if ($folderlist === false) {
			$this->write("The given path is no folder!");
			return;
		}
		else {
			
			// Does the user want extensions?
			if (isset($this->options['no-ext'])) {
				// Remove the extensions from the paths
				$newFolderList = array();
				foreach ($folderlist as $path) {
					// @see http://stackoverflow.com/questions/2395882/how-to-remove-extension-from-string-only-real-extension
					$withoutExt = preg_replace("/\\.[^.\\s]{3,4}$/", "", $path);
					$newFolderList[] = $withoutExt;
				}
				$folderlist = $newFolderList;
			}
			
			$format = isset($this->options['f']) ? $this->options['f'] : "text";
			switch ($format) {
				case ("text"): {
						// Just echo the paths
						foreach ($folderlist as $path) {
							$this->write($path);
						}
						break;
					}
				case ("csv"): {
						// Replace the slashes with ";"
						foreach ($folderlist as $path) {
							$this->write(str_replace(DIRECTORY_SEPARATOR, ";", $path));
						}
						break;
					}
			}
		}
		
	}
	
	/**
	 * Parses the folder structure recursivly and returns an array with the (full) paths to all files fount in the folder.
	 * @param	String	$folder	The path to the start folder.
	 * @return 	Mixed	An Array with paths to all files or false if the path is no folder.
	 */
	protected function parseFolder($folder)
	{
		if (is_dir($folder)) {
			// The result array
			$result = array();
			
			// Scan the directory
			$folder_content = scandir($folder);
			foreach ($folder_content as $filename) {
				// Check if I should ignore the filename
				if (!in_array($filename, $this->ignore_names)) {
					$path = $folder . DIRECTORY_SEPARATOR . $filename;
					
					if (is_dir($path)) {
						// The new path is a directory. Parse it!
						$result = array_merge($result, $this->parseFolder($path));
					}
					else {
						// Truncate the path and add it to the list.
						$result[] = substr($path, $this->input_folder_length + 1);
					}
				}
			}
			
			// Return the result.
			return $result;
		}
		else {
			return false;
		}
	}
}

if (!defined('SCRIPT_STARTED')) {
	// Run the scripts
	$script = new FolderToList();
	$script->run();
}

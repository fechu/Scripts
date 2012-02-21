#!/usr/bin/php
<?php

/**
 *
 * Dieses Skript erledigt einige Aufgaben die Zusammenhang haben mit dem Zend Framework.
 * 
 * @file Zend.php
 * @author Sandro Meier
 * @date 21.02.2012
 * 
 */

 include 'Autoload.php';

// Get the options
$options = getopt("a:p:");

// The action parameter is required.
if (!isset($options['a']) || !isset($options['p']))
{
  help();
  return;
}
else
{
  // Get the path
  $path = $options['p'];
  
  switch($options['a']) {
    case "actions":
      {
        // Is a file or a folder given?
        if (is_dir($path)) 
        {
          $files = scandir($path);
          $files = array_filter($files, 'possibleFile');
	  
          $allActions = array();
          foreach ($files as $file) {
	    $actions = scanFile($path . $file, "action");
	    $allActions[$file] = $actions;
          }
          generateOutput($allActions, "action");
	
        }
        else if (is_file($path) && possibleFile($path)) 
        {
          // Process the file.
          $actions = scanFile($path, "action");
          if (count($actions) > 0)
          {
	    $basename = pathinfo($path, PATHINFO_FILENAME);
	    generateOutput(array($basename => $actions), "action");
          }
          else
          {
	    fwrite(STDOUT, "No Actions found!");
            return;
          }
        }
        break;
      }
    case "properties":
      {
        // Is a file or a folder given?
        if (is_dir($path)) 
        {
          // Add a slash at the end if no slash available
          if (substr($path, -1 ,1) != '/') 
          {
            $path .= '/';
          }

          $files = scandir($path);
          $files = array_filter($files, 'possibleFile');
		
          $allActions = array();
          foreach ($files as $file)
          {
            // Scan for properties
            $actions = scanFile($path . $file, "property");
            $allActions[$file] = $actions;
          }
          generateOutput($allActions, "property");
        }
        else if (is_file($path) && possibleFile($path)) 
        {
          // Process the file.
          $actions = scanFile($path, "property");
          if (count($actions) > 0)
          {
            $basename = pathinfo($path, PATHINFO_FILENAME);
            generateOutput(array($basename => $actions), "property");
          }
          else {
            fwrite(STDOUT, "No Actions found!");
          }
        }
        break;
      }
    default:
      {
        fwrite(STDOUT, "Unknown Action: " . $options['a'] . "\n");
        break;
      }
  }
}
 
/**
 * Zeigt die Hilfe an. 
 */
function help()
{
  fwrite(STDOUT, "\nusage: ./Zend.php -a <action> -p <file/folder>");
  fwrite(STDOUT, "\nOptions\n");
  fwrite(STDOUT, "  a   Die Aktion die ausgefuehrt werden soll. Aktionen:\n");
  fwrite(STDOUT, "        actions:    Parst alle Actions in einem Controller und listet sie auf.\n");
  fwrite(STDOUT, "        properties: Liest alle Properties aus den Doctrine Models. Aufgrund der @Column ,@OneToMany (...) Beschreibung.\n\n");
  fwrite(STDOUT, "  p   Der Pfad zur Datei oder zum Ordner mit der die Aktion ausgefuehrt werden soll.\n\n");
}

/**
 * †berprŸft ob das file am Ÿbergebenen Pfad ein mšgliches File zum scannen ist. 
 * @param String $path Der Pfad zum File.
 */
function possibleFile($path) 
{
  // Only files with extension php
  if (pathinfo($path, PATHINFO_EXTENSION) == "php")
  {
    return true;
  }
  else {
    return false;
  }
}

/**
 * Scannt das File auf alle Actions und gibt einen Array mit den Namen der Actions zurŸck.
 * @param Der Pfad zur Datei die gescannt werden soll.
 * @param Der Typ nach dem gescannt werden soll. 
 */ 
function scanFile($path, $type)
{
  $handle = fopen($path, "r");
  $content = fread($handle, filesize($path));
	
  switch ($type) {
    case "action":
      {
        $regex = "public function ([A-Za-z]*)Action\(\)";
        break;
      }
      case "property":
      {
        $regex = '@(?:Column|OneToMany|ManyToMany|OneToOne).*?protected *\$(\w*)';  
        break;
      }
  }
  
  $search = new SM\Regex\Search($regex, $content);
  $matches = $search->run(true);
  
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
 * @param $actions  Ein Array mit den Namen der Aktionen. Der Array kann multidimensional sein. Dann werden im File Abschnitte erstellt.
 * @param $type     Wie der Output generiert werden soll. 
 */
function generateOutput($actions, $type)
{
  switch ($type) {
    case "action":
    {
      //Check if the first object is an array. 
      foreach ($actions as $key => $fileActions)
      {
        fwrite(STDOUT, "\n--- " . $key . " ---\n");
        foreach ($fileActions as $action)
        {
          fwrite(STDOUT, $action . "\n");
        }
      }
      break;
    }
    case "property":
    {
      // Check if the first object is an array. 
      foreach ($actions as $key => $fileProperties)
      {
	fwrite(STDOUT, "\n--- " . $key . " ---\n");
	foreach($fileProperties as $property)
        {
	    fwrite(STDOUT, $property . "\n");
	}
      }
      break;
    }
  }
}

 ?>
<?php

/**
 *
 * Dieses Skript erm�glich das einfach suchen und ersetzen von Werten in Files.
 * Es sind die folgenden switches vorhanden:
 *
 *    -s <pattern>: Suche nach diesem Regex Pattern (oder string)
 *    -r <pattern>: Ersetze mit diesem Pattern
 * 
 * @file Regex.php
 * @author Sandro Meier
 * @date 21.02.2012
 * 
 */
include 'Autoload.php';

// Get the options
$options = getopt("s:r:");

// The s parameter is required.
if (!isset($options['s']))
{
  help();
  return;
}
else
{
  // Get the file (and the content of it.)
  $filepath = $argv[count($argv) - 1];
  if (in_array($filepath, $options) == TRUE ||  array_search($filepath, $options) == TRUE)
  {
    help();
    return;
  }
  
  $filecontent = file_get_contents($filepath);
  if ($filecontent === FALSE)
  {
    fwrite("Error reading file: $filepath\n");
  }
  
  // Is there a replacement?
  if (isset($options['r']))
  {
    // Search and replace
    $replace = new \SM\Regex\Replace($options['s'], $options['r'], $filecontent);
    fwrite(STDOUT, $replace->run());
  }
  else
  {
    // Only Search
    $search = new \SM\Regex\Search($options['s'], $filecontent);
    $result = $search->run();
    showResult($result);
  }
}



/**
 * Zeigt die Hilfe an. 
 */
function help()
{
  fwrite(STDOUT, "\nusage: ./Regex.php -s <pattern> [-r <pattern] <file>\n");
  fwrite(STDOUT, "\nOptions\n");
  fwrite(STDOUT, "  s   Search for the given pattern.\n");
  fwrite(STDOUT, "  r   Replace the found parts with this pattern\n\n");
}

/**
 * Zeigt das Resultat an. 
 */
function showResult($result)
{
  fwrite(STDOUT, join("\n", $result) . "\n");
}

?>
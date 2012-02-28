#!/usr/bin/php
<?php

/**
 *
 * This scripts has the possibility to return random strings
 *
 *    -l <length>:  The length of the returned string
 *    -n <count>:   Return n random strings 
 * 
 * @file Random.php
 * @author Sandro Meier
 * @date 28.02.2012
 * 
 */

include 'Autoload.php';

// Get the options
$options = getopt("l:n:");

// The l parameter is required.
if (!isset($options['l']))
{
  help();
  return;
}
else
{
  // Return a random string
  $length = $options['l'];
  $count = isset($options['n']) ? $options['n'] : 1;
  
  // All possible characters
    $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
    
    // Return the length
  for($i = 0; $i < $count; $i++)
  {
    // Shuffle the characters
    $characters = str_shuffle($characters);
    
    // Write the random string
    fwrite(STDOUT, substr($characters, 0, $length) . "\n");
  }
}



/**
 * Shows the Help 
 */
function help()
{
  fwrite(STDOUT, "\nUse this script to generate random strings.\n");
  fwrite(STDOUT, "\nusage: ./Random.php -l <length>\n");
  fwrite(STDOUT, "\nOptions\n");
  fwrite(STDOUT, "  l   Length of the returned strings\n");
  fwrite(STDOUT, "  n   Number of random strings\n");

}

?>
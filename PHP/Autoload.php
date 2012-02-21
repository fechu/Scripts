<?php

/**
 * Klassen werden automatisch geladen. 
 **/
function __autoload($className)
{
  $path = str_replace('\\', '/', $className) . ".php";
  
  include $path;
}

?>
<?php namespace SM\Script;

/**
 * The base of all scripts.
 * Perfomrs basic actions like parsing and validating options.
 */
class Script {

    /**
     * @var Array Contains the options
     */
    protected $options = NULL;

    /**
     * Shows the help.
     * This method is supposed to be overriden in a subclass.
     * @return String The help
     */
    protected function help()
    {
	return "No help available";
    }
    
    /**
     * This method is supposed to be overriden in a subclass.
     * You can use this method to initialize some variables or whatever in your script.
     */
    protected function init()
    {
	// Does nothing by default.
    }

    /**
     * Run the script.
     */
    public function run()
    {
        $defined_options = $this->getOptions();
	
	$short_options_list = $defined_options[0];
	$long_options_list = $defined_options[1];
	
	// Build the short options for getopt
	$short_options = "";
	foreach($short_options_list as $option => $value)
	{
	    $short_options .= $option;
	}
	
	// Build the long options for getopt
	$long_options = array();
	foreach($long_options_list as $option => $value)
	{
	    $long_options[] = $option;
	}
	
	// Get the options
	$received_options = getopt($short_options, $long_options);
        
        // If no switches received, show the help
        if (count($received_options) == 0)
        {
          $this->write($this->help());
          return;
        }

	// Verify the received arguments.
	foreach($received_options as $option => $value)
	{
            $option_copy = $option;
            // Get the option
            for($i = 0; $i < 2; $i++)
            {
              if (isset($short_options_list[$option_copy]))
              {
                $should = $short_options_list[$option_copy];
                break;
              }
              else if (isset($long_options_list[$option_copy]))
              {
                $should = $long_options_list[$option_copy];
              }
              else
              {
                // Add ":" to the string. See the getopt documentation for explanation.
                $option_copy .= ":";                
              }
            }
	    
	    if (is_array($should))
	    {
		// Check if it's possible value
		if (!in_array($value, $should))
		{
		    // Received an invalid options
		    $this->write("Received invalid value for option \"" . $option . "\". Possible Values: \n" . join("\n", $should));
		    return;
		}
	    }
	}
	
	
	// Everything verified, start the script!
	$this->options = $received_options;
	$this->init();	
	$this->script();
    }
    
    /**
     * This method does the actions the script should perform. 
     */
    protected function script()
    {
      $this->write("Hello World");
    }
  
    /**
     * @return Array An array with the shortoptions as one string at index 0 and an array with the long options at position
     */
    protected function getOptions()
    {
	// Default is no options
	$shortOptions = array();
	$longOptions = array();
	return array($shortOptions, $longOptions);
    }
    
    /**
     * Writes the string to STDOUT with a newline at the end
     * @param String The string to write
     */
    protected function write($string)
    {
      fwrite(STDOUT, $string . "\n");
    }
}
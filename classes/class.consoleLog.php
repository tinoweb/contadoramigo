<?php
# This Class will only work on PHP v5.2.0+ so we enforce this here
if (strnatcmp(phpversion(),'5.2.0') < 0) { 
	$classFileName = end(explode('/',__FILE__));
	die($classFileName . ' requires PHP version 5.2++<br><br>Please upgrade PHP to use this Class'); 
}

/*
Modifiquei o script para: 
- Classe não ser instanciada automaticamente.
- Aceitar caracteres latinos
- Imprimir caminhos de arquivos (windows)
*/

# Initiate the class upon loading the file
//$console = new consoleLog();

# Set PHP's error handler to our global function that handles this through the class
set_error_handler("consoleLogPHPError");

# Set PHP's exception handler to our global function that handles this through the class
set_exception_handler("consoleLogPHPExceptions");

# Set PHP's exit handler to our global function that handles this through the class (allows catching fatal errors)
register_shutdown_function("consoleLogPHPExit");

/**
 * class.consoleLog.php
 * Used for logging information to the firebug console via PHP.  This file includes three global functions
 * at the end of the file to route set_error_handler(), register_shutdown_function(), and set_exception_handler()
 * which allows the automatic error handling of PHP warnings, errors & exceptions
 *
 * Written by Shane Kretzmann -> http://Shane.Kretzmann.net -> Shane@Kretzmann.net
 */
class consoleLog {

	private $NL; // New Line characters
	private $type; // Used internally to allow $var display in the right area
	// The following variables hold true or false depending on visibilty wanted.
	private $showLog;
	private $showInfo;
	private $showDebug;
	private $showWarn;
	private $showError;
	private $showException;
	
	function __construct() {
		
		// Set new line commands
		$this->NL = "\r\n";
		// default settings to show all console log types
		$this->showLog = $this->showInfo = $this->showDebug = $this->showWarn = $this->showError = $this->showException = true;			
	}

	/** 
	 * Class defaults to everything on, use this function to customize what to show in firebug console and what not to
	 * Pass this function an array of settings to switch things on or off
	 * ie: array('log'=>true,'info'=>false,'debug'=>true,'warnings'=>true,'errors'=>true,'exceptions'=>true)
	 */
	public function settings($array) {
		foreach ($array as $key => $value) {
			switch($key) {
				case 'log':
					$this->showLog = $value;
					break;
				case 'info':
					$this->showInfo = $value;
					break;
				case 'debug':
					$this->showDebug = $value;
					break;
				case 'warnings':
					$this->showWarn = $value;
					break;
				case 'errors':
					$this->showError = $value;
					break;
				case 'exceptions':
					$this->showException = $value;
					break;
					
			}
		}
	}

	/** 
	 * The following public functions simply send different types of console message
	 * Each function accepts $text which is the message to display and $var which can
	 * be a string, array or object for display in the console
	 */
	public function log($text,$var=null) {
		if ($this->showLog) return $this->__processConsoleLog($text,$var,1);
	}
	public function info($text,$var=null) {
		if ($this->showInfo) return $this->__processConsoleLog($text,$var,2);
	}
	public function warn($text,$var=null) {
		if ($this->showWarn) return $this->__processConsoleLog($text,$var,3);
	}
	public function exception($text,$var=null) {
		if ($this->showException) return $this->__processConsoleLog($text,$var,4);
	}
	public function error($text,$var=null) {
		if ($this->showError) return $this->__processConsoleLog($text,$var,4);
	}
	public function debug($text,$var=null) {
		if ($this->showDebug) return $this->__processConsoleLog($text,$var,5);
	}

	/**
	 * This function is the core of the class and creates the necessary
	 * javascript to push the information being passed to the firebug console
	 * It should only be called by an internal class function
	 */
	private function __processConsoleLog($name, $var = null, $type = 1) {
		echo $this->NL . '<script type="text/javascript">' . $this->NL;
		// We need to remove any carriage returns or new lines from within the $name variable
		$name = str_replace(array(chr(13),chr(10)),'',$name);
		
		switch($type) {
			case 1: // Push Log to firebug console
				echo 'if(console && console.log) console.log("'.$name.'");'.$this->NL;
				$this->type = 'log';
			break;
			case 2: // Push Info to firebug console
				echo 'if(console && console.info) console.info("'.$name.'");'.$this->NL;
				$this->type = 'info';
				break;
			case 3: // Push Warning to firebug console
				echo 'if(console && console.warn) console.warn("'.$name.'");'.$this->NL;
				$this->type = 'warn';
			break;
			case 4: // Push Error to firebug console
				echo 'if(console && console.error) console.error("'.$name.'");'.$this->NL;
				$this->type = 'error';
			break;
			case 5: // Push Debug to firebug console
				echo 'if(console && console.debug) console.debug("'.$name.'");'.$this->NL;
				$this->type = 'debug';
			break;
			
		}
		
		function encode_items(&$item, $key)
		{
			$item = utf8_encode($item);
		}		
		
		if (!empty($var)) {
			if (is_object($var) || is_array($var)) {
				array_walk_recursive($var, 'encode_items');
							
				$object = json_encode($var);
				echo 'var object'.preg_replace('~[^A-Z|0-9]~i',"_",$name).' = \''.str_replace("'","\'",$object).'\';'.$this->NL;
				echo 'var val'.preg_replace('~[^A-Z|0-9]~i',"_",$name).' = eval("(" + object'.preg_replace('~[^A-Z|0-9]~i',"_",$name).' + ")" );'.$this->NL;
				echo 'if(console && console.'.$this->type.') console.'.$this->type.'(val'.preg_replace('~[^A-Z|0-9]~i',"_",$name).');'.$this->NL;
			} else { // not an object or array so we will just pass it to the console as a string
				echo 'if(console && console.'.$this->type.'("'.str_replace('"','\\"',$var).'");'.$this->NL;
			}
		}
		
		echo '</script>'.$this->NL;
		
	}
}

/**
* Push PHP Errors to FireBug Console
*/
function consoleLogPHPError($errno,$errstr,$errfile,$errline) {
	global $console; // pull in $console object
    switch ($errno) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $errorType = "Notice";
            $consoleType = "warn";
			break;
        case E_WARNING:
        case E_USER_WARNING:
            $errorType = "Warning";
            $consoleType = "warn";
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $errorType = "Fatal Error";
            $consoleType = "error";
            break;
        default:
            $errorType = "Unknown";
            $consoleType = "warn";
            break;
	}
	$errstr = str_replace('  ',' ',preg_replace('/\[.*?\]:/i','',$errstr)); // no need for the function link back in console log
	$errstr = str_replace("'","\'",$errstr);
	$errstr = str_replace('"',"\'",$errstr);
	
	$errfile = str_replace("\\", "\\\\",$errfile);
	
	if (is_object($console)) $console->$consoleType('[PHP '.$errorType.'][' . $errfile . ':' . $errline . '] '.$errstr);
	if (ini_get('log_errors')) error_log(sprintf("PHP %s: %s in %s on line %d", $errorType, $errstr, $errfile, $errline));
}

/**
* Global Function to Push PHP Fatal Errors to FireBug Console
*/
function consoleLogPHPExit() {
	global $console; // pull in $console object
	if ($err = error_get_last()) {
		$errfile = str_replace("\\", "\\\\", $err['file']);
		
		$errline = $err['line'];
		
		$errstr = $err['message'];
		$errstr = str_replace('  ',' ',preg_replace('/\[.*?\]:/i','',$errstr)); // no need for the href link here
		$errstr = str_replace("'","\'",$errstr);
		$errstr = str_replace('"',"\'",$errstr);
		
		if (is_object($console)) $console->error('[PHP Fatal Error][' . $errfile . ':' . $errline . '] '. $errstr);
	}
}

/**
* Global Function to Push PHP UnCaught Exceptions to FireBug Console
*/
function consoleLogPHPExceptions($e) {
	global $console; // pull in $console object
	$trace = $e->getTrace();
	$errstr = $e->getMessage();
	if (is_object($console)) $console->exception('[PHP Exception]: '.str_replace("'","\'",$errstr),$trace);
}
?>

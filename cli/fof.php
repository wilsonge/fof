<?php
/**
 * @package     FOF
 * @author 		Daniele Rosario (daniele@weble.it)	
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 *
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  --
 *
 *  Command-line generator for FOF3 Save Scaffolding feature
 */

// Define ourselves as a parent file
define('_JEXEC', 1);

// Required by the CMS
define('DS', DIRECTORY_SEPARATOR);

// JSON_PRETTY_PRINT only in PHP 5.4.0
$minphp = '5.4.0';

if (version_compare(PHP_VERSION, $minphp, 'lt')) {
	$curversion = PHP_VERSION;
	$bindir = PHP_BINDIR;
	echo <<< ENDWARNING
================================================================================
WARNING! Incompatible PHP version $curversion
================================================================================
This CRON script must be run using PHP version $minphp or later. Your server is
currently using a much older version which would cause this script to crash. As
a result we have aborted execution of the script. Please contact your host and
ask them for the correct path to the PHP CLI binary for PHP $minphp or later, then
edit your CRON job and replace your current path to PHP with the one your host
gave you.
For your information, the current PHP version information is as follows.
PATH:    $bindir
VERSION: $curversion
Further clarifications:
1. There is absolutely no possible way that you are receiving this warning in
   error. We are using the PHP_VERSION constant to detect the PHP version you
   are currently using. This is what PHP itself reports as its own version. It
   simply cannot lie.
2. Even though your *site* may be running in a higher PHP version that the one
   reported above, your CRON scripts will most likely not be running under it.
   This has to do with the fact that your site DOES NOT run under the command
   line and there are different executable files (binaries) for the web and
   command line versions of PHP.
3. Please note that you MUST NOT ask us for support about this error. We cannot
   possibly know the correct path to the PHP CLI binary as we have not set up
   your server. Your host must know and give that information.
4. The latest published versions of PHP can be found at http://www.php.net/
   Any older version is considered insecure and must NOT be used on a live
   server. If your server uses a much older version of PHP than that please
   notify them that their servers are insecure and in need of an update.
This script will now terminate. Goodbye.
ENDWARNING;
	die();
}

$cwd = getcwd();

// Are we in a joomla site (/cli/fof.php) ?
if (file_exists(dirname(__DIR__) . '/includes/defines.php')) {
	$dir = dirname(__DIR__);
} else {
	// Do we have .fof file?
	if (!file_exists(dirname(__FILE__) . '/.fof')) {
		// TODO: ask the path to a joomla site to create the .fof file
		exit();
	}

	// load from .fof file
	$fof = json_decode(file_get_contents(dirname(__FILE__) . '/.fof'));
	if ($fof && $fof->dev) {
		$dir = $fof->dev;
	}
}

if (!defined('_JDEFINES'))
{
	$path = rtrim($dir, DIRECTORY_SEPARATOR);
	define('JPATH_BASE', $path);
	require_once JPATH_BASE . '/includes/defines.php';
}

// Load the rest of the necessary files
if (file_exists(JPATH_LIBRARIES . '/import.legacy.php'))
{
	require_once JPATH_LIBRARIES . '/import.legacy.php';
}
else
{
	require_once JPATH_LIBRARIES . '/import.php';
}

require_once JPATH_LIBRARIES . '/cms.php';

JLoader::import('joomla.application.cli');
JLoader::import('joomla.application.component.helper');
JLoader::import('cms.component.helper');

/**
 * FOF3 Generator App
 */
class FofApp extends JApplicationCli
{

	protected $admin = true;

	/**
	 * JApplicationCli didn't want to run on PHP CGI. I have my way of becoming
	 * VERY convincing. Now obey your true master, you petty class!
	 *
	 * @param JInputCli $input
	 * @param JRegistry $config
	 * @param JDispatcher $dispatcher
	 */
	public function __construct(JInputCli $input = null, JRegistry $config = null, JDispatcher $dispatcher = null)
	{
		// Close the application if we are not executed from the command line, Akeeba style (allow for PHP CGI)
		if (array_key_exists('REQUEST_METHOD', $_SERVER))
		{
			die('You are not supposed to access this script from the web. You have to run it from the command line. If you don\'t understand what this means, you must not try to use this file before reading the documentation. Thank you.');
		}

		$cgiMode = false;

		if (!defined('STDOUT') || !defined('STDIN') || !isset($_SERVER['argv']))
		{
			$cgiMode = true;
		}
		
		// If a input object is given use it.
		if ($input instanceof JInput)
		{
			$this->input = $input;
		}
		// Create the input based on the application logic.
		else
		{
			if (class_exists('JInput'))
			{
				if ($cgiMode)
				{
					$query = "";
					if (!empty($_GET))
					{
						foreach ($_GET as $k => $v)
						{
							$query .= " $k";
							if ($v != "")
							{
								$query .= "=$v";
							}
						}
					}
					$query	 = ltrim($query);
					$argv	 = explode(' ', $query);
					$argc	 = count($argv);
					$_SERVER['argv'] = $argv;
				}
				if (class_exists('JInputCLI'))
				{
					$this->input = new JInputCLI();
				}
				else
				{
					$this->input = new JInputCli();
				}
			}
		}
		// If a config object is given use it.
		if ($config instanceof JRegistry)
		{
			$this->config = $config;
		}
		// Instantiate a new configuration object.
		else
		{
			$this->config = new JRegistry;
		}
		// If a dispatcher object is given use it.
		if ($dispatcher instanceof JDispatcher)
		{
			$this->dispatcher = $dispatcher;
		}
		// Create the dispatcher based on the application logic.
		else
		{
			$this->loadDispatcher();
		}
		
		// Load the configuration object.
		$this->loadConfiguration($this->fetchConfigurationData());
		
		// Set the execution datetime and timestamp;
		$this->set('execution.datetime', gmdate('Y-m-d H:i:s'));
		$this->set('execution.timestamp', time());
		
		// Set the current directory.
		$this->set('cwd', getcwd());
	}
	
	/**
	 * The main entry point of the application
	 */
	public function execute()
	{
		$this->loadLibraries();
		$this->displayBanner();
		$this->disableTimeLimit();

		// Get arguments
		$args = $this->input->args;

		$composer = $this->getComposerInfo();

		// Register the current namespace with the autoloader
		FOF30\Autoloader\Autoloader::getInstance()->addMap('FOF30\\Generator\\', array(realpath(dirname(__FILE__) . '/fof/')));
		FOF30\Autoloader\Autoloader::getInstance()->register();
			
		// Get command
		$command = array_shift($args);
		$command = ucfirst(strtolower($command));

		// Run automatically every know command
		$class = 'FOF30\Generator\Command\\' . $command;
		$partial_class = $class;

		while (count($args)) {
			$command = array_shift($args);
			$command = ucfirst(strtolower($command));
			$partial_class = $class . '\\' . $command;
			
			if (class_exists($partial_class)) {
				$class = $partial_class;
			}
		}
		
		try {
			if (class_exists($class)) {
				$class = new $class();
				$class->execute($composer, $this->input);
			}
		} catch(Exception $e) {
			$this->out($e);
			exit();
		}
	}

	/**
	 * Load the informations from the composer.json file
	 * @return object The composer file informations
	 */
	protected function getComposerInfo() 
	{
		$this->out("Checking for Existing Composer File...");

		// Does the composer file exists?
		if (!file_exists(getcwd() . '/composer.json')) {
			
			// Ask to create it
			$this->out("Can't find a composer.json file in this directory. Run \"composer init\" to create it");
			exit();
		}

		// Read composer's informations
		$composer = json_decode(file_get_contents(getcwd() . '/composer.json'));

		return $composer;
	}

	/**
	 * Disable PHP time limit
	 */
	protected function disableTimeLimit() 
	{
		// Unset time limits
		$safe_mode = true;
		if (function_exists('ini_get'))
		{
			$safe_mode = ini_get('safe_mode');
		}

		if (!$safe_mode && function_exists('set_time_limit'))
		{
			$this->out("Unsetting time limit restrictions");
			@set_time_limit(0);
		}
	}

	/**
	 * Perform tedious tasks as loading Joomla files, error handling, etc
	 */
	protected function loadLibraries() 
	{
		// Set all errors to output the messages to the console, in order to
		// avoid infinite loops in JError ;)
		restore_error_handler();
		JError::setErrorHandling(E_ERROR, 'die');
		JError::setErrorHandling(E_WARNING, 'echo');
		JError::setErrorHandling(E_NOTICE, 'echo');

		// Required by Joomla!
		JLoader::import('joomla.environment.request');
		JLoader::import('joomla.filesystem.file');
		JLoader::import('joomla.filesystem.folder');

		// Allow inclusion of Joomla! files
		if (!defined('_JEXEC'))
			define('_JEXEC', 1);

		// Load FOF
		if (!defined('FOF30_INCLUDED') && !@include_once(JPATH_LIBRARIES . '/fof30/include.php'))
		{
			die('FOF 3.0 is not installed');
		}
	}

	/**
	 * Display the generator banner informations
	 */
	protected function displayBanner() 
	{
		$year			 = gmdate('Y');
		$phpversion		 = PHP_VERSION;
		$phpenvironment	 = PHP_SAPI;

		$this->out("FOF3 Generator");
		$this->out("Copyright (C) 2010-$year Nicholas K. Dionysopoulos");
		$this->out(str_repeat('-', 79));
		$this->out("FOF3 is Free Software, distributed under the terms of the GNU General");
		$this->out("Public License version 2 or, at your option, any later version.");
		$this->out("This program comes with ABSOLUTELY NO WARRANTY as per sections 15 & 16 of the");
		$this->out("license. See http://www.gnu.org/licenses/gpl-2.0.html for details.");
		$this->out(str_repeat('-', 79));
		$this->out("You are using PHP $phpversion ($phpenvironment)");
		$this->out("");
	}

	/**
	 * Load the Joomla Configuration from specific path
	 *
	 * @param string $path The directory where we should find the configuration.php file 
	 */
	public function reloadConfiguration($path)
	{			
		// Load the configuration object.
		$this->loadConfiguration($this->fetchConfigurationData($path . '/configuration.php'));
	}
}

$app = JApplicationCli::getInstance('FofApp');
\JFactory::$application = $app;
$app->execute();
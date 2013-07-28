<?php
/**
 * Prepares a minimalist framework for unit testing.
 *
 * Joomla is assumed to include the /unittest/ directory.
 * eg, /path/to/joomla/unittest/
 *
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Inflector
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

define('_JEXEC', 1);

// Fix magic quotes.
ini_set('magic_quotes_runtime', 0);

// Maximise error reporting.
ini_set('zend.ze1_compatibility_mode', '0');
error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', 1);

// Timezone fix; avoids errors printed out by PHP 5.3.3+
if(function_exists('date_default_timezone_get') && function_exists('date_default_timezone_set')) {
	if(function_exists('error_reporting')) {
		$oldLevel = error_reporting(0);
	}
	$serverTimezone = @date_default_timezone_get();
	if(empty($serverTimezone) || !is_string($serverTimezone)) $serverTimezone = 'UTC';
	if(function_exists('error_reporting')) {
		error_reporting($oldLevel);
	}
	@date_default_timezone_set( $serverTimezone);
}

// Required by older versions of the CMS
define('DS', DIRECTORY_SEPARATOR);

// Load configuration
require_once __DIR__ . '/../config.php';

// Load system defines
$siteroot = $fofTestConfig['site_root'];

if (file_exists($siteroot . '/defines.php')) {
        include_once $siteroot . '/defines.php';
}
if (!defined('_JDEFINES')) {
        define('JPATH_BASE', $siteroot);
        require_once JPATH_BASE . '/includes/defines.php';
}

if (!defined('JPATH_TESTS'))
{
	define('JPATH_TESTS', realpath(__DIR__ . '/..'));
}

// Import the platform in legacy mode.
if (file_exists(JPATH_LIBRARIES . '/import.legacy.php'))
{
	// Joomla! 2.5
	require_once JPATH_LIBRARIES . '/import.legacy.php';
}
else
{
	// Joomla! 3.x
	require_once JPATH_LIBRARIES . '/import.php';
}

// Bootstrap the CMS libraries.
require_once JPATH_LIBRARIES . '/cms.php';

// Apply the SQL
require_once __DIR__ . '/stubs/dbimport.php';
$importer = new FteststubsDbimport;
$importer->importdb();

// Register the FOF test classes.
JLoader::registerPrefix('Ftest', JPATH_TESTS . '/unit/core');

// Load FOF's autoloader
jimport('joomla.application.input');
require_once __DIR__ . '/../../fof/include.php';
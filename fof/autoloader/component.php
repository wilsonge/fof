<?php
/**
 *  @package     FrameworkOnFramework
 *  @subpackage  autoloader
 *  @copyright   Copyright (c)2010-2012 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2, or later
 */

defined('FOF_INCLUDED') or die();

/**
 * An autoloader for FOF-powered components. It allows the autoloading of
 * various classes related to the operation of a component, from Controllers
 * and Models to Helpers and Fields. If a class doesn't exist, it will be
 * created on the fly.
 */
class FOFAutloaderComponent
{
	/**
	 * An instance of this autoloader
	 *
	 * @var   FOFAutoloaderComponent
	 */
	public static $autoloader = null;

	/**
	 * The path to the FOF root directory
	 *
	 * @var   string
	 */
	public static $fofPath = null;

	/**
	 * Initialise this autoloader
	 *
	 * @return  FOFAutloaderComponent
	 */
	public static function init()
	{
		if (self::$autoloader == NULL)
		{
			self::$autoloader = new self();
		}

		return self::$autoloader;
	}

	/**
	 * Public constructor. Registers the autoloader with PHP.
	 *
	 * @return  void
	 */
	public function __construct()
	{
		self::$fofPath = realpath(__DIR__ . '/../');

		spl_autoload_register(array($this,'autoload_fof_controller'));
		spl_autoload_register(array($this,'autoload_fof_model'));
		spl_autoload_register(array($this,'autoload_fof_view'));
		spl_autoload_register(array($this,'autoload_fof_table'));
		spl_autoload_register(array($this,'autoload_fof_helper'));
		spl_autoload_register(array($this,'autoload_fof_toolbar'));
		spl_autoload_register(array($this,'autoload_fof_field'));
	}

	/**
	 * Creates class aliases. On systems where eval() is enabled it creates a
	 * real class. On other systems it merely creates an alias. The eval()
	 * method is preferred as class_aliases result in the name of the class
	 * being instanciated not being available, making it impossible to create
	 * a class instance without passing a $config array :(
	 *
	 * @param   string   $original  The name of the original (existing) class
	 * @param   string   $alias     The name of the new (aliased) class
	 * @param   boolean  $autoload  Should I try to autoload the $original class?
	 *
	 * @return  void
	 */
	private function class_alias($original, $alias, $autoload = true)
	{
		static $hasEval = null;

		if (is_null($hasEval))
		{
			$hasEval = false;
			if (function_exists('ini_get'))
			{
				$disabled_functions = ini_get('disabled_functions');
				if (!is_string($disabled_functions))
				{
					$hasEval = true;
				}
				else
				{
					$disabled_functions = explode(',', $disabled_functions);
					$hasEval = !in_array('eval', $disabled_functions);
				}
			}
		}

		if (class_exists($original, $autoload))
		{
			$phpCode = "class $alias extends $original {}";
			eval($phpCode);
		}
		else
		{
			class_alias($original, $alias, $autoload);
		}
	}

	/**
	 * Autoload Controllers
	 *
	 * @param   string  $class_name  The name of the class to load
	 *
	 * @return  void
	 */
	public function autoload_fof_controller($class_name)
	{
		static $isCli = null, $isAdmin = null;
		if (is_null($isCli) && is_null($isAdmin))
		{
			list($isCli, $isAdmin) = FOFDispatcher::isCliAdmin();
		}

		if (strpos($class_name, 'Controller') === false)
		{
			return;
		}

		// Change from camel cased into a lowercase array
        $class_modified = preg_replace('/(\s)+/', '_', $class_name);
        $class_modified = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $class_modified));
        $parts = explode('_', $class_modified);

		// We need three parts in the name
		if (count($parts) != 3)
		{
			return;
		}

		// We need the second part to be "controller"
		if ($parts[1] != 'controller')
		{
			return;
		}

		// Get the information about this class
		$component_raw  = $parts[0];
		$component = 'com_' . $parts[0];
		$view = $parts[2];

		// Get the alternate view and class name (opposite singular/plural name)
		$alt_view = FOFInflector::isSingular($view) ? FOFInflector::pluralize($view) : FOFInflector::singularize($view);
		$alt_class = FOFInflector::camelize($component_raw . '_controller_' . $alt_view);

		// Get the proper and alternate paths and file names
		$file = "/components/$component/controllers/$view.php";
		$altFile = "/components/$component/controllers/$alt_view.php";
		$path = ($isAdmin || $isCli) ? JPATH_ADMINISTRATOR : JPATH_SITE;
		$altPath = ($isAdmin || $isCli) ? JPATH_SITE : JPATH_ADMINISTRATOR;

		// Try to find the proper class in the proper path
		if (file_exists($path . $file))
		{
			@include_once $path . $file;
		}

		// Try to find the proper class in the alternate path
		if (!class_exists($class_name) && file_exists($altPath . $file))
		{
			@include_once $altPath . $file;
		}

		// Try to find the alternate class in the proper path
		if (!class_exists($alt_class) && file_exists($path . $altFile))
		{
			@include_once $path . $altFile;
		}

		// Try to find the alternate class in the alternate path
		if (!class_exists($alt_class) && file_exists($altPath . $altFile))
		{
			@include_once $altPath . $altFile;
		}

		// If the alternate class exists just map the class to the alternate
		if (!class_exists($class_name) && class_exists($alt_class))
		{
			$this->class_alias($alt_class, $class_name);
		}
		// No class found? Map to FOFController
		elseif (!class_exists($class_name))
		{
			if ($view != 'default')
			{
				$defaultClass = FOFInflector::camelize($component_raw . '_controller_default');
				$this->class_alias($defaultClass, $class_name, true);
			}
			else
			{
				$this->class_alias('FOFController', $class_name);
			}
		}
	}

	/**
	 * Autoload Models
	 *
	 * @param   string  $class_name  The name of the class to load
	 *
	 * @return  void
	 */
	public function autoload_fof_model($class_name)
	{

	}

	/**
	 * Autoload Views
	 *
	 * @param   string  $class_name  The name of the class to load
	 *
	 * @return  void
	 */
	public function autoload_fof_view($class_name)
	{

	}

	/**
	 * Autoload Tables
	 *
	 * @param   string  $class_name  The name of the class to load
	 *
	 * @return  void
	 */
	public function autoload_fof_table($class_name)
	{

	}

	/**
	 * Autoload Helpers
	 *
	 * @param   string  $class_name  The name of the class to load
	 *
	 * @return  void
	 */
	public function autoload_fof_helper($class_name)
	{

	}

	/**
	 * Autoload Fields
	 *
	 * @param   string  $class_name  The name of the class to load
	 *
	 * @return  void
	 */
	public function autoload_fof_field($class_name)
	{

	}

	/**
	 * Autoload Toolbars
	 *
	 * @param   string  $class_name  The name of the class to load
	 *
	 * @return  void
	 */
	public function autoload_fof_toolbar($class_name)
	{

	}
}
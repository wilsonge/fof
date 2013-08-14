<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('_JEXEC') or die;

/**
 * Base class for rendering a display layout
 * loaded from from a layout file
 *
 * This class searches for Joomla! version override Layouts. For example,
 * if you have run this under Joomla! 3.0 and you try to load
 * mylayout.default it will automatically search for the
 * layout files default.j30.php, default.j3.php and default.php, in this
 * order.
 *
 * @package  FrameworkOnFramework
 * @since    1.0
 */
class FOFLayoutFile extends JLayoutBase
{
	/**
	 * @var    string  Dot separated path to the layout file, relative to base path
	 * @since  3.0
	 */
	protected $layoutId = '';

	/**
	 * @var    string  Base path to use when loading layout files
	 * @since  3.0
	 */
	protected $basePath = null;

	/**
	 * @var    string  Full path to actual layout files, after possible template override check
	 * @since  3.0.3
	 */
	protected $fullPath = false;

	/**
	 * Method to instantiate the file-based layout.
	 *
	 * @param   string  $layoutId  Dot separated path to the layout file, relative to base path
	 * @param   string  $basePath  Base path to use when loading layout files
	 *
	 * @since   3.0
	 */
	public function __construct($layoutId, $basePath = null)
	{
		$this->layoutId = $layoutId;
		$this->basePath = is_null($basePath) ? JPATH_ROOT . '/layouts' : rtrim($basePath, DIRECTORY_SEPARATOR);
	}

	/**
	 * Method to render the layout.
	 *
	 * @param   object  $displayData  Object which properties are used inside the layout file to build displayed output
	 *
	 * @return  string  The necessary HTML to display the layout
	 *
	 * @since   3.0
	 */
	public function render($displayData)
	{
		$layoutOutput = '';

		// Check possible overrides, and build the full path to layout file
		$path = $this->getPath();

		// If there exists such a layout file, include it and collect its output
		if ($path !== false)
		{
			ob_start();
			include $path;
			$layoutOutput = ob_get_contents();
			ob_end_clean();
		}

		return $layoutOutput;
	}

	/**
	 * Method to finds the full real file path, checking possible overrides
	 *
	 * @return  string  The full path to the layout file
	 *
	 * @since   3.0
	 */
	protected function getPath()
	{
		jimport('joomla.filesystem.path');

		if (!$this->fullPath && !empty($this->layoutId))
		{
			$parts = explode('.', $this->layoutId);
			$file  = array_pop($parts);

			$filePath = implode('/', $parts);
			$suffixes = FOFPlatform::getInstance()->getTemplateSuffixes();

			foreach ($suffixes as $suffix)
			{
				$files[] = $file . $suffix . '.php';
			}

			$files[] = $file . '.php';

			$possiblePaths = array(
				JPATH_THEMES . '/' . JFactory::getApplication()->getTemplate() . '/html/layouts/' . $filePath,
				$this->basePath . '/' . $filePath
			);

			reset($files);

			while ((list(, $fileName) = each($files)) && !$this->fullPath)
			{
				$this->fullPath = JPath::find($possiblePaths, $fileName);
			}
		}

		return $this->fullPath;
	}
}

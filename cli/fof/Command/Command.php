<?php

namespace FOF30\Generator\Command;

class Command {
	/**
	 * Get the component's name from the user
	 * @return string The name of the component (com_foobar)
	 */
	protected function getComponentName($composer) 
	{
		$extra = ($composer->extra && $composer->extra->fof) ? $composer->extra->fof : false;
		$default_name = $extra ? $extra->name : array_pop(explode("/", $composer->name));
		$default_name = $default_name ? $default_name : 'com_foobar';

		// Add com_ if necessary
		if (stripos($default_name, "com_") !== 0) {
			$default_name = "com_" . $default_name;
		}

		$name = false;

		if (!$extra) {
			\JFactory::getApplication()->out("What's your component name? (" . $default_name . ")");
			$name = \JFactory::getApplication()->in();
		}
		
		if (!$name) {
			$name = $default_name;
		}

		// Keep asking while the name is not valid
		while(!$name) {
			$name = $this->getComponentName($composer);
		}

		// Add com_ if necessary
		if (stripos($name, "com_") !== 0) {
			$name = "com_" . $name;
		}

		return strtolower($name);
	}

	/**
	 * Load the Joomla Configuration from a dev site
	 * 
	 * @param boolean $force Should we ask the user even if we have a .fof file?
	 */
	public function setDevServer($force = false)
	{	
		// .fof file not found, ask the user!
		if (!\JFile::exists(getcwd() . '/.fof') || $force) {
			$this->out("What's the dev site location? ( /var/www/ )");
			$path = $this->in();

			if (!$path || !\JFolder::exists($path)) {
				$this->out('The path does not exists');
				$this->setDevServer();
			}

			if (!\JFile::exists($path . '/configuration.php')) {
				$this->out('The path does not contain a Joomla Website');
				$this->setDevServer();	
			}

			$fof = array('dev' => $path);
			\JFile::write(getcwd() . '/.fof', json_encode($fof));
		} else {
			$fof = json_decode(\JFile::read(getcwd() . '/.fof'));
			
			if ($fof && $fof->dev) {
				$path = $fof->dev;
			}
		}

		// Load the configuration object.
		\JFactory::getApplication()->reloadConfiguration($path);
	}

	/**
	 * Proxy the in() call to the application
	 */
	protected function in() {
		return \JFactory::getApplication()->in();
	}

	/**
	 * Proxy the out() call to the application
	 */
	protected function out($content) {
		return \JFactory::getApplication()->out($content);
	}
}
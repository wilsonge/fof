<?php

namespace FOF30\Generator\Command;

class Command {
	/**
	 * Get the component's name from the user
	 * @return string The name of the component (com_foobar)
	 */
	protected function getComponentName($composer) 
	{
		$extra = $composer->extra ? $composer->extra->fof : false;
		$default_name = $extra ? $extra->name : array_pop(explode("/", $composer->name));
		$default_name = $default_name ? $default_name : 'com_foobar';

		// Add com_ if necessary
		if (stripos($default_name, "com_") !== 0) {
			$default_name = "com_" . $default_name;
		}

		\JFactory::getApplication()->out("What's your component name? (" . $default_name . ")");
		$name = \JFactory::getApplication()->in();
		
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

	protected function in() {
		return \JFactory::getApplication()->in();
	}

	protected function out($content) {
		return \JFactory::getApplication()->out($content);
	}
}
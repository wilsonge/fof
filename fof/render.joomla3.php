<?php
/**
 *  @package FrameworkOnFramework
 *  @copyright Copyright (c)2010-2012 Nicholas K. Dionysopoulos
 *  @license GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;

/**
 * Joomla! 3.0.0-alpha2 view renderer class 
 */
class FOFRenderJoomla3 extends FOFRenderStrapper
{
	public function __construct() {
		$this->priority = 55;
		if(version_compare(JVERSION, '3.0', 'ge')) {
			$version = strtolower(JVERSION);
			if(substr($version, -7) == '_alpha2') $this->enabled = true;
		}
	}
	
	public function preRender($view, $task, $input, $config=array())
	{
		$this->renderLinkbar($view, $task, $input, $config);
	}
	
	public function postRender($view, $task, $input, $config=array())
	{
	}
	
}
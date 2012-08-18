<?php
/**
 *  @package FrameworkOnFramework
 *  @copyright Copyright (c)2010-2012 Nicholas K. Dionysopoulos
 *  @license GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;

/**
 * Default Joomla! 1.5, 1.7, 2.5 view renderer class 
 */
class FOFRenderJoomla extends FOFRenderAbstract
{
	public function __construct() {
		$this->priority = 50;
		$this->enabled = true;
	}
	
	/**
	 * Echoes any HTML to show before the view template
	 * 
	 * @param string $view The current view
	 * @param string $task The current task
	 * @param array $input The input array (request parameters)
	 */
	public function preRender($view, $task, $input, $config=array())
	{
		$this->renderLinkbar($view, $task, $input, $config);
	}
	
	/**
	 * Echoes any HTML to show after the view template
	 * 
	 * @param string $view The current view
	 * @param string $task The current task
	 * @param array $input The input array (request parameters)
	 */
	public function postRender($view, $task, $input, $config=array())
	{
		
	}
	
	protected function renderLinkbar($view, $task, $input, $config=array())
	{
		// Do not render a submenu unless we are in the the admin area
		list($isCli, $isAdmin) = FOFDispatcher::isCliAdmin();
		if(!$isAdmin) return;
		$toolbar = FOFToolbar::getAnInstance(FOFInput::getCmd('option','com_foobar',$input), $config);
		$links = $toolbar->getLinks();
		if(!empty($links)) {
			foreach($links as $link) {
				JSubMenuHelper::addEntry($link['name'], $link['link'], $link['active']);
			}
		}
	}
}
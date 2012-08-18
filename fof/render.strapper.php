<?php
/**
 *  @package FrameworkOnFramework
 *  @copyright Copyright (c)2010-2012 Nicholas K. Dionysopoulos
 *  @license GNU General Public License version 3, or later
 */

defined('_JEXEC') or die;

/**
 * Akeeba Strapper view renderer class.
 */
class FOFRenderStrapper extends FOFRenderAbstract
{
	public function __construct() {
		$this->priority = 60;
		$this->enabled = class_exists('AkeebaStrapper');
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
		echo "<div class=\"akeeba-bootstrap\">\n";
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
		echo "</div>\n";
	}
	
	protected function renderLinkbar($view, $task, $input, $config=array())
	{
		// Do not render a submenu unless we are in the the admin area
		list($isCli, $isAdmin) = FOFDispatcher::isCliAdmin();
		if(!$isAdmin) return;
		$toolbar = FOFToolbar::getAnInstance(FOFInput::getCmd('option','com_foobar',$input), $config);
		$links = $toolbar->getLinks();
		if(!empty($links)) {
			echo "<ul class=\"nav nav-tabs\">\n";
			foreach($links as $link) {
				echo "<li";
				if($link['active']) echo ' class="active"';
				echo ">";
				if($link['icon']) {
					echo "<i class=\"icon icon-".$link['icon']."\"></i>";
				}
				if($link['link']) {
					echo "<a href=\"".$link['link']."\">".$link['name']."</a>";
				} else {
					echo $link['name'];
				}
				
				echo "</li>\n";
			}
			echo "</ul>\n";
		}
	}
}
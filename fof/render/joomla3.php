<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Joomla! 3 view renderer class 
 */
class FOFRenderJoomla3 extends FOFRenderStrapper
{
	/**
	 * Public constructor. Determines the priority of this class and if it should be enabled
	 */
	public function __construct() {
		$this->priority = 55;
		$this->enabled = version_compare(JVERSION, '3.0', 'ge');
	}
}
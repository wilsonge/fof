<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * FrameworkOnFramework table behavior class. It defines the events which are
 * called by a Table.
 *
 * @package  FrameworkOnFramework
 * @since    2.1
 */
abstract class FOFTableBehavior extends JEvent
{
	/**
	 * This event runs before binding data to the table
	 *
	 * @param   FOFModel  &$table  The table which calls this event
	 * @param   array     &$data   The data to bind
	 *
	 * @return  void
	 */
	public function onBeforeBind(&$table, &$data)
	{

	}

	onAfterLoad(&$table, &$from)
}

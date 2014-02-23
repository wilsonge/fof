<?php
/**
 * @package    FrameworkOnFramework
 * @subpackage form
 * @copyright  Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('FOF_INCLUDED') or die;

/**
 * User group field header
 *
 * @package  FrameworkOnFramework
 * @since    2.0
 */
class FOFFormHeaderUsergroup extends FOFFormHeaderFieldselectable
{
	/**
	 * Method to get the list of user groups
	 *
	 * @return  array	A list of user groups.
	 *
	 * @since   2.0
	 */
	protected function getOptions()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('a.id AS value, a.title AS text');
		$query->from('#__usergroups AS a');
		$query->group('a.id, a.title');
		$query->order('a.id ASC');
		$query->order($query->qn('title') . ' ASC');

		// Get the options.
		$db->setQuery($query);
		$options = $db->loadObjectList();

		return $options;
	}
}
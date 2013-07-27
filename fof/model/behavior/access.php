<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * FrameworkOnFramework model behavior class to filter front-end access to items
 * based on the viewing access levels.
 *
 * @package  FrameworkOnFramework
 * @since    2.1
 */
class FOFModelBehaviorAccess extends FOFModelBehavior
{
	/**
	 * This event runs after we have built the query used to fetch a record
	 * list in a model. It is used to apply automatic query filters.
	 *
	 * @param   FOFModel        &$model  The model which calls this event
	 * @param   JDatabaseQuery  &$query  The model which calls this event
	 *
	 * @return  void
	 */
	public function onAfterBuildQuery(&$model, &$query)
	{
		// This behavior only applies to the front-end.
		if (!FOFPlatform::getInstance()->isFrontend())
		{
			return false;
		}

		// Get the name of the access field
		$table = $model->getTable();
		$accessField = $table->getColumnAlias('access');

		// Make sure the access field actually exists
		if (!in_array($accessField, $table->getKnownFields()))
		{
			return false;
		}

		// Get the authorised access levels of the current user
		$access_levels = JFactory::getUser()->getAuthorisedViewLevels();

		// And filter the query output by these access levels
		$db = JFactory::getDbo();
		$access_levels = array_map(array($db, 'quote'), $access_levels);
		$query->where($db->qn($accessField) . ' IN (' . implode(',', $access_levels) . ')');
	}
}

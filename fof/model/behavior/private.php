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
 * craeted by the currently logged in user only.
 *
 * @package  FrameworkOnFramework
 * @since    2.1
 */
class FOFModelBehaviorPrivate extends FOFModelBehavior
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
		$createdField = $table->getColumnAlias('created_by');

		// Make sure the access field actually exists
		if (!in_array($createdField, $table->getKnownFields()))
		{
			return false;
		}

		// Get the current user's id
		$user_id = JFactory::getUser()->id;

		// And filter the query output by the user id
		$db = JFactory::getDbo();
		$query->where($db->qn($createdField) . ' = ' . $db->q($user_id));
	}
}

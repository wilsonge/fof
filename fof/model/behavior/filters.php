<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

/**
 * FrameworkOnFramework model behavior class
 *
 * @package  FrameworkOnFramework.Model
 * @since    2.2
 */
class FOFModelBehaviorFilters extends FOFModelBehavior
{
	public function onAfterBuildQuery(&$model, &$query)
	{
		$table = $model->getTable();
		$tableName = $table->getTableName();
		$tableKey = $table->getKeyName();
		$db = $model->getDBO();

		$fields = $model->getTableFields();

		foreach ($fields as $fieldname => $fieldtype)
		{
			$field = new stdClass();
			$field->name = $fieldname;
			$field->type = $fieldtype;
			
			$filterName = ($field->name == $tableKey) ? 'id' : $field->name;
			$filterState = $model->getState($filterName, null);

			$field = FOFModelField::getField($field, array('dbo' => $db));

			if ($sql = $field->partial($filterState, 10))
			{
				$query->where($sql);
			}
		}
	}
}
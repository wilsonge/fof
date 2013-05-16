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

			if (is_array($filterState) || is_object($filterState))
			{
				$options = new JRegistry($filterState);
			} else 
			{
				$options = new JRegistry();
				$options->set('value', $filterState);
			}

			$methods = $field->getSearchMethods();
			$method = $options->get('method', $field->getDefaultSearchMethod());

			if (!in_array($method, $methods))
			{
				$method = 'exact';
			}

			switch ($method)
			{
				case 'between':
				case 'outside':
					$sql = $field->$method($options->get('from', null), $options->get('to'));
					break;

				case 'interval':
					$sql = $field->$method($options->get('value', null), $options->get('interval'));
					break;

				case 'exact':
				case 'partial':
				case 'search':
				default:
					$sql = $field->$method($options->get('value', null));
					break;
			}

			if ($sql)
			{
				$query->where($sql);
			}
		}
	}
}
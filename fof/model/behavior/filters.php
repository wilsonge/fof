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
			$filterName = ($fieldname == $tableKey) ? 'id' : $fieldname;
			$filterState = $model->getState($filterName, null);

			if ($filterName == $table->getColumnAlias('enabled'))
			{
				if (!is_null($filterState) && ($filterState !== ''))
				{
					$query->where($db->qn($fieldname) . ' = ' . $db->q((int) $filterState));
				}
			}
			elseif (!empty($filterState) || ($filterState === '0'))
			{
				switch ($fieldname)
				{
					case $table->getColumnAlias('title'):
					case $table->getColumnAlias('description'):
						$query->where('(' . $db->qn($fieldname) . ' LIKE ' . $db->q('%' . $filterState . '%') . ')');

						break;

					default:
						if (is_array($filterState))
						{
							$tmp = array();
							foreach ($filterState as $k => $v)
							{
								$tmp[] = $db->q($v);
							}
							$query->where('(' . $db->qn($fieldname) . ' IN(' . implode(',', $tmp) . '))');
						}
						else
						{
							$query->where('(' . $db->qn($fieldname) . '=' . $db->q($filterState) . ')');
						}
						break;
				}
			}
		}
	}
}
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
class FOFModelFieldDate extends FOFModelFieldText
{
	public function getDefaultSearchMethod()
	{
		return 'exact';
	}

	/**
	 * Interval date search
	 * 	
	 * @param  string  				$value    The value to search
	 * @param  string|array|object  $interval The interval. Can be (+1 MONTH or array('value' => 1, 'unit' => 'MONTH', 'sign' => '+'))
	 * @param  boolean 				$include  If the borders should be included
	 * 
	 * @return string           	the sql string
	 */
	public function interval($value, $interval, $include = true)
	{
		if ($this->isEmpty($value) || $this->isEmpty($interval))
		{
			return '';
		}

		$interval = $this->getInterval($interval);

		if ($interval['sign'] == '+')
		{
			$function = 'DATE_ADD';
		} else 
		{
			$function = 'DATE_SUB';
		}

		$extra = '';

		if ($include)
		{
			$extra = '=';
		}
		
		$sql = '(' . $this->_db->qn($this->name) . ' >' . $extra . ' ' . $function;
		$sql .= '(' . $this->_db->quote($value) . ', INTERVAL ' . $interval['value'] . ' ' . $interval['unit']  . '))';

		return $sql;
	}

	protected function getInterval($inteval)
	{
		if (is_string($inteval))
		{
			if (strlen($interval) > 2)
			{
				$interval = explode(" ", $interval);
				$sign = ($interval[0] == '-') ? '-' : '+';
				$value = (int) substr($interval[0], 1);

				$interval = array(
					'unit' => $inteval[1],
					'value' => $value,
					'sign' => $sign
				);	
			} else 
			{
				$interval = array(
					'unit' => 'MONTH',
					'value' => 1,
					'sign' => '+'
				);	
			}
		} else 
		{
			$interval = (array) $inteval;
		}

		return $interval;
	}
}
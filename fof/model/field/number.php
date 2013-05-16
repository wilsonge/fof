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
class FOFModelFieldNumber extends FOFModelField
{
	public function partial($value)
	{
		return $this->exact($value);
	}

	public function between($from, $to, $include = true)
	{
		if ($this->isEmpty($from) || $this->isEmpty($to))
		{
			return '';
		}
		
		$extra = '';
		
		if ($include)
		{
			$extra = '=';
		}

		$sql =  '((' . $this->_db->qn($this->name)  . ' >' . $extra . ' ' . $from .') AND ';
		$sql .= '(' . $this->_db->qn($this->name)  . ' <' . $extra . ' ' . $to .'))';

		return $sql;
	}

	public function outside($from, $to, $include = false)
	{
		if ($this->isEmpty($from) || $this->isEmpty($to))
		{
			return '';
		}
		
		$extra = '';
		
		if ($include)
		{
			$extra = '=';
		}

		$sql =  '((' . $this->_db->qn($this->name)  . ' <' . $extra . ' ' . $from .') AND ';
		$sql .= '(' . $this->_db->qn($this->name)  . ' >' . $extra . ' ' . $to .'))';

		return $sql;
	}

	public function interval($value, $interval, $include = true)
	{
		if ($this->isEmpty($value))
		{
			return '';
		}
		
		$from = $value - $interval;
		$to = $value + $interval;

		$extra = '';
		
		if ($include)
		{
			$extra = '=';
		}

		$sql =  '((' . $this->_db->qn($this->name)  . ' >' . $extra . ' ' . $from .') AND ';
		$sql .= '(' . $this->_db->qn($this->name)  . ' <' . $extra . ' '  . $to .'))';

		return $sql;
	}

}
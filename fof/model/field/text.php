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
 * @since    2.1
 */
class FOFModelFieldText extends FOFModelField
{
	public function __construct($db, $field)
	{
		parent::__construct($db, $field);

		$this->null_value = '';
	}

	public function getDefaultSearchMethod()
	{
		return 'partial';
	}

	public function partial($value)
	{
		if ($this->isEmpty($value))
		{
			return '';
		}

		return '(' . $this->_db->qn($this->name) . ' LIKE ' . $this->_db->quote('%' . $value . '%') . ')';
	}

	public function exact($value)
	{
		if ($this->isEmpty($value))
		{
			return '';
		}

		return '(' . $this->_db->qn($this->name) . ' LIKE ' . $this->_db->quote( $value ) . ')';
	}

	public function between($from, $to, $include = true)
	{
		return '';
	}

	public function outside($from, $to, $include = false)
	{
		return '';
	}

	public function interval($value, $interval, $include = true)
	{
		return '';
	}

}

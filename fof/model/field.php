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
abstract class FOFModelField
{
	protected $_db = null;

	/**
	 * The column name of the table field
	 *
	 * @var string
	 */
	protected $name = '';

	/**
	 * The column type of the table field
	 *
	 * @var string
	 */
	protected $type = '';

	/**
	 * The null value for this type
	 *
	 * @var  mixed
	 */
	public $null_value = null;

	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db     The database object
	 * @param   object           $field  The field informations as taken from the db
	 */
	public function __construct($db, $field)
	{
		$this->_db = $db;

		$this->name = $field->name;
		$this->type = $field->type;
	}

	public function isEmpty($value)
	{
		return ($value === $this->null_value) || empty($value);
	}

	public function getDefaultSearchMethod()
	{
		return 'exact';
	}

	public function getSearchMethods()
	{
		$ignore = array('isEmpty', 'getField', 'getFieldType', '__construct', 'getDefaultSearchMethod', 'getSearchMethods');

		$class = new ReflectionClass(__CLASS__);
		$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);

		$tmp = array();
		foreach ($methods as $method)
		{
			$tmp[] = $method->name;
		}
		$methods = $tmp;

		if ($methods = array_diff($methods, $ignore))
		{
			return $methods;
		}

		return array();
	}

	public function exact($value)
	{
		if ($this->isEmpty($value))
		{
			return '';
		}

		return $this->search($value, '=');
	}

	abstract public function partial($value);

	abstract public function between($from, $to, $include = true);

	abstract public function outside($from, $to, $include = false);

	abstract public function interval($from, $interval);

	public function search($value, $operator = '=')
	{
		if ($this->isEmpty($value))
		{
			return '';
		}

		return '(' . $this->_db->qn($this->name) . ' ' . $operator .  ' ' . $this->_db->quote($value) . ')';
	}

	/**
	 * Creates a field Object based on the field column type
	 *
	 * @param  	object 	$field 	The field informations
	 * @param  	array  	$config The field configuration (like the db object to use)
	 *
	 * @return 	FOFModelField	The Field object
	 */
	public static function getField($field, $config = array())
	{
		$type = $field->type;

		$classType = self::getFieldType($type);

		$className = 'FOFModelField' . $classType;

		if (class_exists($className))
		{
			if (isset($config['dbo']))
			{
				$db = $config['dbo'];
			}
			else
			{
				$db = JFactory::getDBO();
			}

			$field = new $className($db, $field);

			return $field;
		}

		return false;
	}

	/**
	 * Get the classname based on the field Type
	 *
	 * @param  	string 	$type 	The type of the field
	 *
	 * @return  string 	the class suffix
	 */
	public static function getFieldType($type)
	{
		switch ($type)
		{
			case 'varchar':
			case 'text':
			case 'smalltext':
			case 'longtext':
			case 'char':
			case 'mediumtext':
				$type = 'Text';
				break;

			case 'date':
			case 'datetime':
			case 'time':
			case 'year':
			case 'timestamp':
				$type = 'Date';
				break;

			default:
				$type = 'Number';
				break;
		}

		return $type;
	}
}

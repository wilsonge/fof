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
 * @package  FrameworkOnFramework
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

	/**
	 * Is it a null or otherwise empty value?
	 *
	 * @param   mixed  $value  The value to test for emptiness
	 *
	 * @return  boolean
	 */
	public function isEmpty($value)
	{
		return ($value === $this->null_value) || empty($value);
	}

	/**
	 * Returns the default search method for a field. This always returns 'exact'
	 * and you are supposed to override it in specialised classes. The possible
	 * values are exact, partial, between and outside, unless something
	 * different is returned by getSearchMethods().
	 *
	 * @see  self::getSearchMethods()
	 *
	 * @return  string
	 */
	public function getDefaultSearchMethod()
	{
		return 'exact';
	}

	/**
	 * Return the search methods available for this field class,
	 *
	 * @return  array
	 */
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

	/**
	 * Perform an exact match (equality matching)
	 *
	 * @param   mixed  $value  The value to compare to
	 *
	 * @return  string  The SQL where clause for this search
	 */
	public function exact($value)
	{
		if ($this->isEmpty($value))
		{
			return '';
		}

		return $this->search($value, '=');
	}

	/**
	 * Perform a partial match (usually: search in string)
	 *
	 * @param   mixed  $value  The value to compare to
	 *
	 * @return  string  The SQL where clause for this search
	 */
	abstract public function partial($value);

	/**
	 * Perform a between limits match (usually: search for a value between
	 * two numbers or a date between two preset dates). When $include is true
	 * the condition tested is:
	 * $from <= VALUE <= $to
	 * When $include is false the condition tested is:
	 * $from < VALUE < $to
	 *
	 * @param   mixed    $from     The lowest value to compare to
	 * @param   mixed    $to       The higherst value to compare to
	 * @param   boolean  $include  Should we include the boundaries in the search?
	 *
	 * @return  string  The SQL where clause for this search
	 */
	abstract public function between($from, $to, $include = true);

	/**
	 * Perform an outside limits match (usually: search for a value outside an
	 * area or a date outside a preset period). When $include is true
	 * the condition tested is:
	 * (VALUE <= $from) || (VALUE >= $to)
	 * When $include is false the condition tested is:
	 * (VALUE < $from) || (VALUE > $to)
	 *
	 * @param   mixed    $from     The lowest value of the excluded range
	 * @param   mixed    $to       The higherst value of the excluded range
	 * @param   boolean  $include  Should we include the boundaries in the search?
	 *
	 * @return  string  The SQL where clause for this search
	 */
	abstract public function outside($from, $to, $include = false);

	/**
	 * Perform an interval search (usually: a date interval check)
	 *
	 * @param   string               $from      The value to search
	 * @param   string|array|object  $interval  The interval
	 *
	 * @return  string  The SQL where clause for this search
	 */
	abstract public function interval($from, $interval);

	/**
	 * Return the SQL where clause for a search
	 *
	 * @param   mixed   $value     The value to search for
	 * @param   string  $operator  The operator to use
	 *
	 * @return  string  The SQL where clause for this search
	 */
	public function search($value, $operator = '=')
	{
		if ($this->isEmpty($value))
		{
			return '';
		}

		return '(' . $this->_db->qn($this->name) . ' ' . $operator . ' ' . $this->_db->quote($value) . ')';
	}

	/**
	 * Creates a field Object based on the field column type
	 *
	 * @param   object  $field   The field informations
	 * @param   array   $config  The field configuration (like the db object to use)
	 *
	 * @return  FOFModelField  The Field object
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
	 * @param   string  $type  The type of the field
	 *
	 * @return  string  the class suffix
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

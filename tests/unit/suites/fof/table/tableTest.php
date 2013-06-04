<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Inflector
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

// I rember that there was a way to autoload the base test, but I can't remember how :(
require_once JPATH_TESTS.'/FofDatabaseTestCase.php';

class FOFTableTest extends FofDatabaseTestCase
{
	public function testReset()
	{
		FOFTable::forceInstance(null);
		$db = JFactory::getDbo();

		$table = $this->getMock('FOFTable',												// Class name to mock
								array('onBeforeReset'),									// Methods to mock
								array('#__foftest_foobars', 'foftest_id_foobar', &$db),	// Construct arguments
								'',
								true,
								true,
								true,
								true);

		$table->expects($this->any())
			  ->method('onBeforeReset')
			  ->will($this->returnValue(false));

		$this->assertFalse($table->reset(), 'Reset should return FALSE when onBeforeReset returns FALSE');

		unset($table);

		// Rebuild the mock to return true on onBeforeReset
		$table = $this->getMock('FOFTable',												// Class name to mock
								array('onBeforeReset'),									// Methods to mock
								array('#__foftest_foobars', 'foftest_id_foobar', &$db),	// Construct arguments
								'',
								true,
								true,
								true,
								true);

		$table->expects($this->any())
			  ->method('onBeforeReset')
			  ->will($this->returnValue(true));

		//$this->assertFalse($table->reset(), 'Reset should return FALSE when onBeforeReset returns FALSE');
	}

	public function testGetUcmCoreAlias()
	{
		FOFTable::forceInstance(null);

		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));

		$table 		= FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);
		$reflection = new ReflectionClass($table);

		$method  = $reflection->getMethod('getUcmCoreAlias');
		$method->setAccessible(true);

		$table->propertyExist = 'dummy';
		$alias = $method->invokeArgs($table, array('propertyExist'));
		$this->assertEquals('propertyExist', $alias, 'Invalid value for existant property');

		$alias = $method->invokeArgs($table, array('propertyDoesNotExist'));
		$this->assertEquals('null', $alias, 'Invalid value for non-existant property');

		$table->testalias = 'aliased property';
		$table->setColumnAlias('testcolumn', 'testalias');
		$alias = $method->invokeArgs($table, array('testcolumn'));
		$this->assertEquals('testalias', $alias, 'Invalid value for aliased property');
	}

	/**
	 * @dataProvider getTestGetContentType
	 */
	public function testGetContentType($option, $view, $expected, $message)
	{
		FOFTable::forceInstance(null);

		$config['input'] = new FOFInput(array('option' => $option, 'view' => $view));

		$table = FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);
		$this->assertEquals($expected, $table->getContentType(), $message);
	}

	public function getTestGetContentType()
	{
		$data[] = array('com_foftest', 'foobar', 'com_foftest.foobar', 'Wrong content type');
		$data[] = array('com_foftest', 'foobars', 'com_foftest.foobar', 'Wrong content type');

		return $data;
	}
}
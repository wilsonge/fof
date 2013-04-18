<?php
/**
 * @package    FrameworkOnFramework.UnitTest
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */
//require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Autoload.php';

require_once (BASEPATH.'/fof/table/table.php');

include_once BASEPATH.'/fof/include.php';
if(!defined('FOF_INCLUDED')) {
	JError::raiseError ('500', 'FOF is not installed');
}

/**
 * Test class for fof inflector.
 *
 * @package    FrameworkOnFramework.UnitTest
 */
class tableTest extends JoomlaDatabaseTestCase
{		
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		if (!defined('JPATH_COMPONENT')) {
			define('JPATH_COMPONENT', JPATH_BASE.'/components/com_foobar');
		}
	
		jimport('joomla.application.input');
	}
	
	public function getSetTriggerEventsData()
	{
		return array( 'true' => array(true, true, 'Should return true'), 
		              'words' => array(false, false, "Should return false"),
		);		
	}
	
	/**
	 * test SetTriggerEvents function
	 * 
	 * @param string $word
	 * @param string $expect
	 * @param string $message
	 * @return void
	 * @dataProviderr getSetTriggerEventsData
	 */
// 	public function testSetTriggerEvents($value, $expect, $message)
// 	{
// 		$obj = FOFTable::getAnInstance();
// 		$this->assertType($obj, 'FOFTable');
// 	}
	
	/**
	 * test pluralize function
	 * 
	 * @param string $word
	 * @param string $expect
	 * @param string $message
	 * @return void
	 * @dataProviderr getSetTriggerEventsData
	 */
	public function testGetAnInstance()
	{
		$stub_dis = $this->getMockBuilder('FOFDispatcher')
		             ->disableOriginalConstructor()
		             ->getMock();
		$stub_dis->expects($this->any())
		         ->method('isCliAdmin')
		         ->will($this->returnValue(true));
		try {
			$obj = FOFTable::getAnInstance('bar', 'FooTable', array('option' => 'com_foo'));
		}
		catch (JDatabaseException $e) {			
			$this->markTestSkipped(
					'Missing foo_bars test table'
			);
		}
		$this->assertInstanceOf('FOFTable', $obj, 'missing table ?');
		
		return $obj;
	}
}

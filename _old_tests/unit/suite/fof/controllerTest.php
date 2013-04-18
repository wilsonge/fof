<?php
/**
 * @package    FrameworkOnFramework.UnitTest
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */
//require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Autoload.php';

require_once BASEPATH.'/fof/include.php';

/**
 * Test class for fof controller.
 *
 * @package    FrameworkOnFramework.UnitTest
 */
class controllerTest extends JoomlaTestCase
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
	
	/**
	 * test get instance
	 * 
	 * @return FOFController instance
	 */
	public function testGetAnInstance()
	{
		$obj = FOFController::getAnInstance('dummy');
		$this->assertInstanceOf('FOFController', $obj);
		return $obj;
	}
	
	public function testExecuteDummyTask()
	{		
		$stub = $this->getMock('FOFController', array('display'));
		$stub->expects($this->once())
		     ->method('display');		
		$this->assertEquals(null, $stub->execute('adummytask'));
	}
	
}

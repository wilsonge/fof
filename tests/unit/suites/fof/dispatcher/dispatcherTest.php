<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Inflector
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

class FOFDispatcherTest extends FtestCase
{
	public function testOnBeforeDispatch()
	{
		$dispatcher = FOFDispatcher::getTmpInstance();

		$this->assertTrue($dispatcher->onBeforeDispatch(), 'onBeforeDispatch should return TRUE');
	}

	public function testOnBeforeDispatchCli()
	{
		$dispatcher = FOFDispatcher::getTmpInstance();

		$this->assertTrue($dispatcher->onBeforeDispatchCLI(), 'onBeforeDispatchCLI should return TRUE');
	}

	public function getTestGetTask()
	{
		$message = 'Incorrect task';

		// Should we test for ids on other cases, too?
		$data[] = array(new FOFInput(array('ids' => array(999))), 'foobar' , true,  'GET' 	 , 'read'  , $message);
		$data[] = array(new FOFInput(array('ids' => array(999))), 'foobar' , false,  'GET' 	 , 'edit'  , $message);
		$data[] = array(new FOFInput(array('id' => 999)), 'foobar' , true,  'GET' 	 , 'read'  , $message);
		$data[] = array(new FOFInput(array('id' => 999)), 'foobar' , false, 'GET' 	 , 'edit'  , $message);
		$data[] = array(new FOFInput(array())           , 'foobar' , true,  'GET'  	 , 'add'   , $message);
		$data[] = array(new FOFInput(array('id' => 999)), 'foobar' , true,  'POST'	 , 'save'  , $message);
		$data[] = array(new FOFInput(array())           , 'foobar' , true,  'POST'	 , 'edit'  , $message);
		$data[] = array(new FOFInput(array('id' => 999)), 'foobar' , true,  'PUT' 	 , 'save'  , $message);
		$data[] = array(new FOFInput(array())           , 'foobar' , true,  'PUT' 	 , 'edit'  , $message);
		$data[] = array(new FOFInput(array('id' => 999)), 'foobar' , true,  'DELETE' , 'delete'  , $message);
		$data[] = array(new FOFInput(array())           , 'foobar' , true,  'DELETE' , 'edit'  , $message);
		$data[] = array(new FOFInput(array('id' => 999)), 'foobars', true,  'GET' 	 , 'browse', $message);
		$data[] = array(new FOFInput(array())           , 'foobars', true,  'GET' 	 , 'browse', $message);
		$data[] = array(new FOFInput(array('id' => 999)), 'foobars', true,  'POST'	 , 'save'  , $message);
		$data[] = array(new FOFInput(array())           , 'foobars', true,  'POST'	 , 'browse', $message);

		return $data;
	}

	/**
	 * @dataProvider getTestGetTask
	 */
	public function testGetTask($input, $view, $frontend, $method, $expected, $message)
	{
		$mockPlatform = $this->getMock('FOFPlatformJoomlaPlatform', array('isFrontend'));
		$mockPlatform->expects($this->any())
					 ->method('isFrontend')
					 ->will($this->returnValue($frontend));

		FOFPlatform::forceInstance($mockPlatform);

		$_SERVER['REQUEST_METHOD'] = $method;
		$dispatcher = FOFDispatcher::getTmpInstance();
		$reflection = new ReflectionClass($dispatcher);

		$property = $reflection->getProperty('input');
		$property->setAccessible(true);

		$method  = $reflection->getMethod('getTask');
		$method->setAccessible(true);

		$property->setValue($dispatcher, $input);
		$task = $method->invokeArgs($dispatcher, array($view));
		$this->assertEquals($expected, $task, $message);
	}

	public function test_createDecryptionKey()
	{
		$dispatcher = FOFDispatcher::getTmpInstance();
		$reflection = new ReflectionClass($dispatcher);

		$encrypt = new FOFEncryptBase32;
		$base32  = $encrypt->encode('FOF rocks!');

		$property = $reflection->getProperty('fofAuth_Key');
		$property->setAccessible(true);
		$property->setValue($dispatcher, $base32);

		$method  = $reflection->getMethod('_createDecryptionKey');
		$method->setAccessible(true);

		// Let's call the method I want to test
		$key = $method->invokeArgs($dispatcher, array(1370123514));

		$this->assertEquals('86b618ea6f2793ad6df388fe47f8883b8a5ac3fd57ac477de77cdce578339737',
							$key,
							'Decryption key is not the expected one');
	}
}
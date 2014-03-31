<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Dispatcher
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'dispatcherDataprovider.php';

class FOFDispatcherTest extends FtestCase
{
    public function setUp()
    {
        parent::setUp();

        FOFPlatform::forceInstance(null);
    }

    /**
     * @group           FOFDispatcher
     * @covers          FOFDispatcher::onBeforeDispatch
     */
	public function testOnBeforeDispatch()
	{
		$dispatcher = FOFDispatcher::getTmpInstance();

		$this->assertTrue($dispatcher->onBeforeDispatch(), 'onBeforeDispatch should return TRUE');
	}

    /**
     * @group           FOFDispatcher
     * @covers          FOFDispatcher::onBeforeDispatchCLI
     */
	public function testOnBeforeDispatchCli()
	{
		$dispatcher = FOFDispatcher::getTmpInstance();

		$this->assertTrue($dispatcher->onBeforeDispatchCLI(), 'onBeforeDispatchCLI should return TRUE');
	}

	/**
     * @group           FOFDispatcher
     * @group           dispatcherGetTak
     * @covers          FOFDispatcher::getTask
	 * @dataProvider    getTestGetTask
	 */
	public function testGetTask($input, $view, $frontend, $method, $expected, $message)
	{
		$mockPlatform = $this->getMock('FOFIntegrationJoomlaPlatform', array('isFrontend'));
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

    /**
     * @TODO This test should be removed, since the tested function is no longer used and it's a private
     * method, so we should not test it.
     *
     * @group           FOFDispatcher
     * @group           dispatchercreateDecryptionKey
     * @covers          FOFDispatcher::_createDecryptionKey
     */
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

    public function getTestGetTask()
    {
        return DispatcherDataprovider::getTestGetTask();
    }
}
<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Dispatcher
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'dispatcherDataprovider.php';

class F0FDispatcherTest extends FtestCase
{
    public function setUp()
    {
        parent::setUp();

        F0FPlatform::forceInstance(null);
    }

    /**
     * @group           F0FDispatcher
     * @group           dispatcherDispatch
     * @covers          F0FDispatcher::dispatch
     * @dataProvider    getTestDispatch
     */
    public function testDispatch($test, $check)
    {
        $platform = $this->getMock('F0FIntegrationJoomlaPlatform', array('isCli', 'raiseError', 'authorizeAdmin', 'setHeader'));
        $platform->expects($this->any())->method('isCli')->will($this->returnValue($test['isCli']));
        $platform->expects($this->any())->method('authorizeAdmin')->will($this->returnValue($test['auth']));

        $matcher = $check['result'] ? $this->never() : $this->once();
        $platform->expects($matcher)->method('raiseError');

        F0FPlatform::forceInstance($platform);

        $input = array_merge(array('option' => 'com_foftest'), $test['input']);

        $config = array(
            'input' => new F0FInput($input)
        );

        $dispatcher = $this->getMock('F0FDispatcher', array('onBeforeDispatch', 'onBeforeDispatchCLI', 'onAfterDispatch'), array($config));
        $dispatcher->expects($this->any())->method('onBeforeDispatch')->will($this->returnValue($test['before']));
        $dispatcher->expects($this->any())->method('onBeforeDispatchCLI')->will($this->returnValue($test['beforeCli']));
        $dispatcher->expects($this->any())->method('onAfterDispatch')->will($this->returnValue($test['after']));

        // I will ask to phpUnit to create a mock with a fixed name, in this way F0FController::getTmpInstance
        // will find the object and initialize it, using the mocked one
        // The only downside is that we can't controll it (eg stubbing and mocking)
        $view = F0FInflector::pluralize($input['view']);
        $this->getMock('F0FController', array('execute'), array(), 'FoftestController'.ucfirst($view));

        $dispatcher->dispatch();
    }

    /**
     * @group           F0FDispatcher
     * @covers          F0FDispatcher::onBeforeDispatch
     */
	public function testOnBeforeDispatch()
	{
		$dispatcher = F0FDispatcher::getTmpInstance();

		$this->assertTrue($dispatcher->onBeforeDispatch(), 'onBeforeDispatch should return TRUE');
	}

    /**
     * @group           F0FDispatcher
     * @covers          F0FDispatcher::onBeforeDispatchCLI
     */
	public function testOnBeforeDispatchCli()
	{
		$dispatcher = F0FDispatcher::getTmpInstance();

		$this->assertTrue($dispatcher->onBeforeDispatchCLI(), 'onBeforeDispatchCLI should return TRUE');
	}

	/**
     * @group           F0FDispatcher
     * @group           dispatcherGetTak
     * @covers          F0FDispatcher::getTask
	 * @dataProvider    getTestGetTask
	 */
	public function testGetTask($input, $view, $frontend, $method, $expected, $message)
	{
		$mockPlatform = $this->getMock('F0FIntegrationJoomlaPlatform', array('isFrontend'));
		$mockPlatform->expects($this->any())
					 ->method('isFrontend')
					 ->will($this->returnValue($frontend));

		F0FPlatform::forceInstance($mockPlatform);

		$_SERVER['REQUEST_METHOD'] = $method;
		$dispatcher = F0FDispatcher::getTmpInstance();
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
     * @group           F0FDispatcher
     * @group           dispatcherTransparentAuthentication
     * @covers          F0FDispatcher::transparentAuthentication
     * @dataProvider    getTestTransparentAuthentication
     */
    public function testTransparentAuthentication($test, $check)
    {
        $platform = $this->getMock('F0FIntegrationJoomlaPlatform', array('getUser', 'loginUser'));
        $platform->expects($this->any())->method('getUser')->will($this->returnValue((object) array('guest' => $test['guest'])));

        if($check['login'])
        {
            $platform->expects($this->atLeastOnce())->method('loginUser')->will($this->returnValue(true));
        }
        else
        {
            $platform->expects($this->never())->method('loginUser');
        }

        F0FPlatform::forceInstance($platform);

        if(isset($test['server']))
        {
            $_SERVER = array_merge($_SERVER, $test['server']);
        }

        $input = array('format' => $test['format']);

        if(isset($test['input']))
        {
            $input = array_merge($input, $test['input']);
        }

        $config = array(
            'input' => new F0FInput($input)
        );

        $dispatcher = new F0FDispatcher($config);

        if(isset($test['authKey']))
        {
            $property = new ReflectionProperty($dispatcher, 'fofAuth_Key');
            $property->setAccessible(true);
            $property->setValue($dispatcher, $test['authKey']);
        }

        $dispatcher->transparentAuthentication();
    }

    /**
     * @TODO This test should be removed, since the tested function is no longer used and it's a private
     * method, so we should not test it.
     *
     * @group           F0FDispatcher
     * @group           dispatchercreateDecryptionKey
     * @covers          F0FDispatcher::_createDecryptionKey
     */
    public function test_createDecryptionKey()
	{
		$dispatcher = F0FDispatcher::getTmpInstance();
		$reflection = new ReflectionClass($dispatcher);

		$encrypt = new F0FEncryptBase32;
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

    public function getTestDispatch()
    {
        return DispatcherDataprovider::getTestDispatch();
    }

    public function getTestGetTask()
    {
        return DispatcherDataprovider::getTestGetTask();
    }

    public function getTestTransparentAuthentication()
    {
        return DispatcherDataprovider::getTestTransparentAuthentication();
    }
}
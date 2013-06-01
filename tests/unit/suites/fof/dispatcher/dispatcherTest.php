<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Inflector
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for FOFInflector
 */
class FOFDispatcherTest extends PHPUnit_Framework_TestCase
{
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
		$key = $method->invokeArgs($dispatcher, array($base32));

		$this->assertEquals('c96cdbff0d9ff340e35ecf826bab15893a4fb956c238420660de169dc84139e5', $key);
	}
}
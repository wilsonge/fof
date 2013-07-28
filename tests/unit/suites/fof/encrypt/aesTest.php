<?php
/**
 * @package     FrameworkOnFramework.UnitTest
 * @subpackage  FOFEncryptAES
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for FOFEncryptAES
 *
 * @package  FrameworkOnFramework.UnitTest
 * @since    x.y
 */
class FOFEncryptAesTest extends PHPUnit_Framework_TestCase
{
	/**
	 * [setUp description]
	 *
	 * @return  void
	 */
	protected function setUp()
	{
		// Check if PHP has mcrypt installed
		if (function_exists('mcrypt_module_open'))
		{
			$this->theThing = new FOFEncryptAes('x123456789012345678901234567890x');
		}
	}

	/**
	 * [tearDown description]
	 *
	 * @return  void
	 */
	protected function tearDown()
	{
		// Grab a beer
	}

	/*
	 *	TESTS
	 */

	/**
	 * [testCryptProcess description]
	 *
	 * @return  void
	 */
	public function testCryptProcess()
	{
		if (function_exists('mcrypt_module_open'))
		{
			// Only run test when PHP has mcrypt installed
			$str = 'THATISINSANE';

			$es  = $this->theThing->encryptString($str, true);
			$ds  = $this->theThing->decryptString($es, true);
			$ds  = rtrim($ds, "\000");
			$this->assertNotEquals($str, $es);
			$this->assertEquals($str, $ds);

			$es  = $this->theThing->encryptString($str, false);
			$ds  = $this->theThing->decryptString($es, false);
			$ds  = rtrim($ds, "\000");
			$this->assertNotEquals($str, $es);
			$this->assertEquals($str, $ds);
		}
	}
}

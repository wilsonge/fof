<?php
/**
 * @package     FrameworkOnFramework.UnitTest
 * @subpackage  FOFEncryptBase32
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for FOFEncryptBase32
 *
 * @package  FrameworkOnFramework.UnitTest
 * @since    x.y
 */
class FOFEncryptBase32Test extends PHPUnit_Framework_TestCase
{
	/**
	 * [setUp description]
	 *
	 * @return  void
	 */
	protected function setUp()
	{

		$this->theThing = new FOFEncryptBase32;
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
	 * [testGetVar description]
	 *
	 * @return  void
	 */
	public function testEncode()
	{
		$this->assertEquals('MFRGGZDFMZTWQ2LLNRWW433QOFZHG5DVOZ3XQ6L2GEZDGNBVGY3TQOJQIFBEGRCFIZDUQSKLJRGU4T2QKFJFGVCVKZLVQWK2FIRS2LRMEERMFJZEEUTC6KBJHU7UAQCALQVA',
			$this->theThing->encode('abcdefghiklmnopqrstuvwxyz1234567890ABCDEFGHIKLMNOPQRSTUVWXYZ*#-.,!"§$%&/()=?@@@\*')
			);
	}

	/**
	 * [testGetVar description]
	 *
	 * @return  void
	 */
	public function testDecode()
	{
		$this->assertEquals('abcdefghiklmnopqrstuvwxyz1234567890ABCDEFGHIKLMNOPQRSTUVWXYZ*#-.,!"§$%&/()=?@@@\*',
			$this->theThing->decode('MFRGGZDFMZTWQ2LLNRWW433QOFZHG5DVOZ3XQ6L2GEZDGNBVGY3TQOJQIFBEGRCFIZDUQSKLJRGU4T2QKFJFGVCVKZLVQWK2FIRS2LRMEERMFJZEEUTC6KBJHU7UAQCALQVA')
			);
	}

}

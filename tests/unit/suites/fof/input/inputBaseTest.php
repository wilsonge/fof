<?php
/**
 * @package     FrameworkOnFramework.UnitTest
 * @subpackage  Input
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for FOFString
 *
 * @package  FrameworkOnFramework.UnitTest
 * @since    x.y
 */
class FOFInputTestBase extends PHPUnit_Framework_TestCase
{
	/**
	 * [setUp description]
	 *
	 * @return  void
	 */
	protected function setUp()
	{

		$data = array(
					'var1' => 'one',
					'var2' => 'two',
					'int13' => 13,
					'anarray' => array('a' => 'A','B' => 'b','z' => 'Z'),
					'var3' => 'three',
					'var4' => 'four'
				);

		$this->FOFInput = new FOFInput($data);
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
	public function testGetVar()
	{
		$this->assertEquals('one', $this->FOFInput->get('var1'));
		$this->assertEquals('two', $this->FOFInput->get('var2'));
		$this->assertEquals('three', $this->FOFInput->get('var3'));
		$this->assertEquals('four', $this->FOFInput->get('var4'));
		$this->assertEquals(13, $this->FOFInput->get('int13'));
		$this->assertEquals(true, is_array($this->FOFInput->get('anarray')));
		$this->assertArrayHasKey('a', $this->FOFInput->get('anarray'));
		$this->assertArrayHasKey('B', $this->FOFInput->get('anarray'));
		$this->assertArrayHasKey('z', $this->FOFInput->get('anarray'));
	}

	/**
	 * [testGetVarDefault description]
	 *
	 * @return  void
	 */
	public function testGetVarDefault()
	{
		$this->assertEquals('one', $this->FOFInput->get('var1', 'dumdidilidum'));
		$this->assertEquals('myDefaulfValue', $this->FOFInput->get('var100', 'myDefaulfValue'));
	}

	/**
	 * [testGetData description]
	 *
	 * @return  void
	 */
	public function testGetData()
	{
		$theData = $this->FOFInput->getData();
		$this->assertEquals('one', $theData['var1']);
		$this->assertEquals('two', $theData['var2']);
		$this->assertEquals('three', $theData['var3']);
		$this->assertEquals('four', $theData['var4']);
	}

	/**
	 * [testMagicGet description]
	 *
	 * @return  void
	 */
	public function testMagicGet()
	{
		$this->assertEquals('one', $this->FOFInput->getString('var1'));
		$this->assertEquals(13, $this->FOFInput->getInt('int13'));
	}

}

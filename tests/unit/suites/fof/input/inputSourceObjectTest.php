<?php
/**
 * @package     FrameworkOnFramework.UnitTest
 * @subpackage  Input
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'inputBaseTest.php';

/**
 * Test class for FOFString
 *
 * @package  FrameworkOnFramework.UnitTest
 * @since    x.y
 */
class FOFInputObjTest extends FOFInputTestBase
{
	/**
	 * [setUp description]
	 *
	 * @return  void
	 */
	protected function setUp()
	{

		$data = new StdClass;
		$data->var1 = 'one';
		$data->var2 = 'two';
		$data->int13 = 13;
		$data->anarray = array('a' => 'A','B' => 'b','z' => 'Z');
		$data->var3 = 'three';
		$data->var4 = 'four';

		$this->FOFInput = new FOFInput($data);
	}

}

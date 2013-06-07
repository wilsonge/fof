<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for FOFTemplateUtils
 */
class FOFTemplateUtilsTest extends PHPUnit_Framework_TestCase
{
	/**
	* Test to addCSS method
	*/
	public function testAddCSS()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
	
	/**
	* Test to addJS method
	*/
	public function testAddJS()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
	
	/**
	* Test to addLESS method
	*/
	public function testAddLESS()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
	
	/**
	* Test to sefSort method
	*/
	public function testSefSort()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}

	/**
	* Test to getAltPaths method
	*
	* @dataProvider getTestGetAltPaths
	*/
	public function testGetAltPaths($path, $expect, $normal, $message)
	{
		$altpath = FOFTemplateUtils::getAltPaths($path);
		$this->assertEquals(
			$expect,
			$altpath[$normal],
			$message
		);
	}
	
	public function getTestGetAltPaths()
	{
		return array(
			array('media://com_finder/css/dates.css', 'media/com_finder/css/dates.css', 'normal', 'media:// should be changed into media location'),
			array('admin://com_finder/css/dates.css', 'administrator/com_finder/css/dates.css', 'normal', 'admin:// should be changed into administrator path'),
			array('site://com_finder/css/dates.css', 'com_finder/css/dates.css', 'normal', 'site:// should be changed into site path'),
		);
	}
	
	/**
	* Test to parsePath method
	*/
	public function testParsePath()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
	
	/**
	* Test to route method
	*/
	public function testRoute()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
}
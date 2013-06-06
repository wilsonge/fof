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
class FOFTemplateUtilsTest extends FtestCase
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		parent::setUp();

		// Force a JDocumentHTML instance
		$this->saveFactoryState();
		JFactory::$document = JDocument::getInstance('html');

		// Fake the server variables to get JURI working right
		global $_SERVER;
		$_SERVER['HTTP_HOST'] = 'www.example.com';
		$_SERVER['REQUEST_URI'] = '/index.php?option=com_foobar';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		// Fake the session
		JFactory::$session = $this->getMockSession();
		$application = JFactory::getApplication('site');

		// Fake the template
		$template = (object)array(
			'template'		=> 'system',
		);
		$attribute = new ReflectionProperty($application, 'template');
		$attribute->setAccessible(TRUE);
		$attribute->setValue($application, $template);

		// Replace the FOFPlatform with our fake one
		$this->saveFOFPlatform();
		$this->replaceFOFPlatform();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
		$this->restoreFactoryState();
		$this->restoreFOFPlatform();

		parent::tearDown();
	}

	/**
	 * Test to addCSS method
	 *
	 * @param   string  $path     CSS path to add
	 * @param   string  $expect   Rendered CSS path to expect
	 * @param   string  $message  Message on failure
	 *
	 * @return  void
	 *
	 * @dataProvider getTestAddCSS
	 */
	public function testAddCSS($path, $expect, $message)
	{
		$document = FOFPlatform::getInstance()->getDocument();
		FOFTemplateUtils::addCSS($path);

		$styleSheets = $this->readAttribute($document, '_styleSheets');

		$this->assertArrayHasKey($expect, $styleSheets, $message);
	}

	public function getTestAddCSS()
	{
		return array(
			array('media://com_finder/css/dates.css', 'http://www.example.com/media/com_finder/css/dates.css', 'media:// should be changed into media location'),
			array('admin://com_finder/css/dates.css', 'http://www.example.com/administrator/com_finder/css/dates.css', 'admin:// should be changed into administrator path'),
			array('site://com_finder/css/dates.css', 'http://www.example.com/com_finder/css/dates.css', 'site:// should be changed into site path'),
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
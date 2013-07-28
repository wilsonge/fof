<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  hal.link
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for FOFHalLink
 */
class FOFHalLinkTest extends PHPUnit_Framework_TestCase
{
	public function getTestCreateNoExceptionData()
	{
		return array(
			array('http://www.example.com/nada.json', false, null, null, null, 'Untemplated link without name should be created'),
			array('http://www.example.com/nada{?id}.json', true, null, null, null, 'Templated link without name should be created'),
			array('http://www.example.com/nada.json', false, 'Test name', null, null, 'Untemplated link with name should be created'),
			array('http://www.example.com/nada{?id}.json', true, 'Test name', null, null, 'Templated link with name should be created'),
			array('http://www.example.com/nada.json', false, 'Test name', 'en-GB', null, 'Untemplated link with hreflang should be created'),
			array('http://www.example.com/nada{?id}.json', true, 'Test name', 'en-GB', null, 'Templated link with hreflang should be created'),
			array('http://www.example.com/nada.json', false, 'Test name', 'en-GB', 'My title', 'Untemplated link with title should be created'),
			array('http://www.example.com/nada{?id}.json', true, 'Test name', 'en-GB', 'My title', 'Templated link with title should be created'),
		);
	}

	/**
	 * @dataProvider getTestCreateNoExceptionData
	 */
	public function testCreateNoException($href, $templated, $name, $hreflang, $title, $message)
	{
		try
		{
			$result = new FOFHalLink($href, $templated, $name, $hreflang, $title);
		}
		catch (Exception $exc)
		{
			$this->fail($message);
		}
	}

	public function getTestCreateExceptionData()
	{
		return array(
			array(null, false, null, null, null, 'Null link is not allowed'),
			array('', false, null, null, null, 'Empty link is not allowed'),
		);
	}

	/**
	 * @dataProvider				getTestCreateExceptionData
	 * @expectedException			RuntimeException
	 * @expectedExceptionMessage	A HAL link must always have a non-empty href
	 */
	public function testCreateException($href, $templated, $name, $hreflang, $title, $message)
	{
		$result = new FOFHalLink($href, $templated, $name, $hreflang, $title);
	}


	public function getTestCheckData()
	{
		return array(
			array('http://www.example.com/nada.json', false, true, 'Absolute URL link should always be considered non-empty'),
			array('nada.json', false, true, 'Relative URL link should always be considered non-empty'),
			array('http://www.example.com/nada{?id}.json', false, true, 'Absolute templated URL should always be considered non-empty'),
			array('nada{?id}.json', false, true, 'Relative templated URL should always be considered non-empty'),
		);
	}

	/**
	 * @dataProvider getTestCheckData
	 */
	public function testCheck($href, $templated, $expect, $message)
	{
		$halLink = new FOFHalLink($href, $templated);
		$this->assertEquals($halLink->check(), $expect, $message);
	}

	public function getTestMagicGetterData()
	{
		return array(
			array('href', 'http://www.example.com/nada.json', 'The href property cannot be gotten'),
			array('templated', false, 'The templated property cannot be gotten'),
			array('name', 'My name', 'The name property cannot be gotten'),
			array('hreflang', 'en-GB', 'The hreflang property cannot be gotten'),
			array('title', 'My title', 'The title property cannot be gotten'),
			array('invalidwhatever', null, 'An invalid property should not be gotten'),
		);
	}

	/**
	 * @dataProvider getTestMagicGetterData
	 */
	public function testMagicGetter($property, $expect, $message)
	{
		$link = new FOFHalLink('http://www.example.com/nada.json', false, 'My name', 'en-GB', 'My title');
	}
}
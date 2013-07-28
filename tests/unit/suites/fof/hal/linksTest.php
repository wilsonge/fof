<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  hal.link
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for FOFHalLinks
 */
class FOFHalLinksTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers FOFHalLinks::addLink
	 */
	function testAddLink()
	{
		// Create a sample link
		$link = new FOFHalLink('http://www.example.com/nada.json');
		$linkset = new FOFHalLinks();

		// ==== Add a link to a link set ====
		$result = $linkset->addLink('custom', $link);

		$links = $this->readAttribute($linkset, '_links');

		$this->assertArrayHasKey('custom', $links, 'The link set must have an array key for our rel');

		$this->assertInternalType('object', $links['custom']);

		$this->assertEquals($link, $links['custom'], 'The link item is not present in the link set');

		// ==== Replace a link in the link set ====
		$newlink = new FOFHalLink('http://www.example.com/yeah.json', false, 'Something');
		$result = $linkset->addLink('custom', $newlink, true);

		$links = $this->readAttribute($linkset, '_links');

		$this->assertArrayHasKey('custom', $links, 'The link set must have an array key for our replaced rel');

		$this->assertInternalType('object', $links['custom']);

		$this->assertEquals($newlink, $links['custom'], 'The replaced link item is not present in the link set');

		// ==== Add a link in the link set ====

		$anotherlink = new FOFHalLink('http://www.example.com/another.json', false, 'Something else');
		$result = $linkset->addLink('custom', $anotherlink, false);

		$links = $this->readAttribute($linkset, '_links');

		$this->assertArrayHasKey('custom', $links, 'The link set must have an array key for our replaced rel');

		$this->assertInternalType('array', $links['custom']);

		$this->assertEquals($newlink, $links['custom'][0]);
		$this->assertEquals($anotherlink, $links['custom'][1]);
	}
}
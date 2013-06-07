<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  String
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Test class for FOFString
 */
class FOFStringUtilsTest extends PHPUnit_Framework_TestCase
{
	/**
	* Test to slug method
	*
	* @dataProvider getTestToSlug
	*/
	public function testToSlug($word, $expect, $message)
	{
		$string = FOFStringUtils::toSlug($word);
		$this->assertEquals(
			$expect,
			$string,
			$message
		);
	}
	
	public function getTestToSlug()
	{
		return array(
			array("foobar", "foobar", 'String foobar returns as is'),
			array("foo-bar", "foo-bar", 'Hypens should be left in place'),
			array("foo bar", "foo-bar", 'Spaces should be replaced with hypens'),
			array("foo*bar", "foobar", 'Non-alphanumeric should be removed'),
			array("foo&bar=foo", "fooabarfoo", 'Non-alphanumeric should be removed'),
			array("abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmopqrstuvwxyzabcdefghijklmnopqrstuvwxyz", "abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuv", 'Strings over 100 characters should be limited to 100'),
			array("Foobar", "foobar", 'Strings should be forced into lowercase'),
		);
	}
	
	/**
	* Test to ASCII method
	*
	* @dataProvider getTestToASCII
	*/
	public function testToASCII($word, $expect, $message)
	{
		$string = FOFStringUtils::toSlug($word);
		$this->assertEquals(
			$expect,
			$string,
			$message
		);
	}
	
	public function getTestToASCII()
	{
		return array(
			array("foobar", "foobar", 'String foobar returns as is'),
			array("foo-bar", "foo-bar", 'Hypens should be left in place'),
			array("foo bar", "foo-bar", 'Spaces should be replaced with hypens'),
			array("foo*bar", "foobar", 'Non-alphanumeric should be removed'),
			array("foo&bar=foo", "fooabarfoo", 'Non-alphanumeric should be removed'),
		);
	}
}
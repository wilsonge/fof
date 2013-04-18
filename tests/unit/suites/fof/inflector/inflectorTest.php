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
class FOFInflectorTest extends PHPUnit_Framework_TestCase
{
	public function getTestPluralizeData()
	{
		return array(
			array("word", "words", 'Should return plural'),
		    array("words", "words", "Should return the same as it's already a plural"),
		    array("person", "people", "Special cases not honoured"),
		    array("onyx", "onyxes", "Special cases not honoured"),
		);
	}

	public function getTestSingularizeData()
	{
		return array(
			array("words", "word", 'Should return singular'),
		    array("word", "word", "Should return the same as it's already a singular"),
		    array("people", "person", "Special cases not honoured (person)"),
		    array("onyxes", "onyx", "Special cases not honoured (onyx)"),
		);
	}

	public function getTestCamelizeData()
	{
		return array(
			array("foo_bar", "FooBar", 'Underscores must act as camelization points'),
			array("foo bar", "FooBar", 'Spaces must act as camelization points'),
			array("foo's bar", "FooSBar", 'Punctuation must be stripped out'),
			array("foo.bar.123", "FooBar123", 'Numbers must be preserved'),
		);
	}

	public function getTestUnderscoreData()
	{
		return array(
			array("foo bar", "foo_bar", 'Spaces must act as underscore points'),
			array("FooBar", "foo_bar", 'CamelCase must be converted'),
		);
	}

	public function getTestExplodeData()
	{
		return array(
			array("foo bar", array('foo', 'bar'), 'Spaces must act as underscore points'),
			array("FooBar", array('foo', 'bar'), 'CamelCase must be converted'),
		);
	}

	public function getTestImplodeData()
	{
		return array(
			array(array('foo', 'bar'), "FooBar", 'Implosion failed'),
		);
	}

	public function getTestHumanizeData()
	{
		return array(
			array("foo_bar", 'Foo Bar', 'Humanize failed'),
			array("this_is_a_test", 'This Is A Test', 'Humanize failed'),
		);
	}

	public function getTestTableizeData()
	{
		return array(
			array("person", 'people', 'Pluralise words'),
			array("people", 'people', 'Retain plural forms'),
			array("SomeGoodPerson", 'some_good_people', 'Pluralise camelcase words'),
		);
	}

	public function getTestClassifyData()
	{
		return array(
			array("people", 'Person', 'Singularize words'),
			array("person", 'Person', 'Retain singular forms'),
			array("SomeGoodPeople", 'Somegoodperson', 'Singularize camelcase words'),
		);
	}

	public function getTestVariableizeData()
	{
		return array(
			array("foo_bar", "fooBar", 'Underscores must act as camelization points'),
			array("foo bar", "fooBar", 'Spaces must act as camelization points'),
			array("foo's bar", "fooSBar", 'Punctuation must be stripped out'),
			array("foo.bar.123", "fooBar123", 'Numbers must be preserved'),
		);
	}

	/**
	 * Test addWord method
	 */
	public function testAddWord()
	{
		FOFInflector::addWord('xoxosingular', 'xoxoplural');

		$res = FOFInflector::singularize('xoxoplural');
		$this->assertEquals($res, 'xoxosingular', 'Custom word could not be singularized');

		$res = FOFInflector::pluralize('xoxosingular');
		$this->assertEquals($res, 'xoxoplural', 'Custom word could not be pluralized');
	}

	/**
	 * Test pluralize method
	 *
	 * @dataProvider getTestPluralizeData
	 */
	public function testPluralize($word, $expect, $message)
	{
		$res = FOFInflector::pluralize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test singularize method
	 *
	 * @dataProvider getTestSingularizeData
	 */
	public function testSingularize($word, $expect, $message)
	{
		$res = FOFInflector::singularize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test camelize method
	 *
	 * @dataProvider getTestCamelizeData
	 */
	public function testCamelize($word, $expect, $message)
	{
		$res = FOFInflector::camelize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test underscore method
	 *
	 * @dataProvider getTestUnderscoreData
	 */
	public function testUnderscore($word, $expect, $message)
	{
		$res = FOFInflector::underscore($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test explode method
	 *
	 * @dataProvider getTestExplodeData
	 */
	public function testExplode($word, $expect, $message)
	{
		$res = FOFInflector::explode($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test implode method
	 *
	 * @dataProvider getTestImplodeData
	 */
	public function testImplode($word, $expect, $message)
	{
		$res = FOFInflector::implode($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test humanize method
	 *
	 * @dataProvider getTestHumanizeData
	 */
	public function testHumanize($word, $expect, $message)
	{
		$res = FOFInflector::humanize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test tableize method
	 *
	 * @dataProvider getTestTableizeData
	 */
	public function testTableize($word, $expect, $message)
	{
		$res = FOFInflector::tableize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test classify method
	 *
	 * @dataProvider getTestClassifyData
	 */
	public function testClassify($word, $expect, $message)
	{
		$res = FOFInflector::classify($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

	/**
	 * Test variableize method
	 *
	 * @dataProvider getTestVariableizeData
	 */
	public function testVariableize($word, $expect, $message)
	{
		$res = FOFInflector::variablize($word);
		$this->assertEquals(
			$res,
			$expect,
			$message
		);
	}

}
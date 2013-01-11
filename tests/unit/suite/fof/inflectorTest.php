<?php
/**
 * @package    FrameworkOnFramework.UnitTest
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */
//require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Autoload.php';

require_once (BASEPATH.'/fof/inflector/inflector.php');

/**
 * Test class for fof inflector.
 *
 * @package    FrameworkOnFramework.UnitTest
 */
class inflectorTest extends JoomlaTestCase
{	
	
	public function getTestPluralizeData()
	{
		return array( 'word' => array("word", "words", 'Should return plural'), 
		              'words' => array("words", "words", "Should return the same as it's already a plural"),
		);		
	}
	
	/**
	 * test pluralize function
	 * 
	 * @param string $word
	 * @param string $expect
	 * @param string $message
	 * @return void
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
	
	
	public function getTestAddWordData()
	{
		return array( 'pig' => array("pig", "piggies", 'the plural of pig should be piggies, and vice-versa'),
		);
	}
	
	/**
	 * test add word function
	 * @param string $singular
	 * @param string $plural
	 * @param string $message
	 * @return void
	 * @dataProvider getTestAddWordData
	 */
	public function testAddWord($singular, $plural, $message)
	{
		FOFInflector::addWord($singular, $plural);
		
		$res = FOFInflector::pluralize($singular);		
		$this->assertEquals(
			$res,
			$plural,
			$message
		);
		
		$res = FOFInflector::singularize($plural);		
		$this->assertEquals(
			$res,
			$singular,
			$message
		);
	}
	
	
	public function getTestCamelizeData()
	{
		return array( 'foo_bar' => array("foo_bar", "FooBar", 'foo_bar should be camelized to FooBar'),
		              'foo bar' => array("foo bar", "FooBar", 'foo bar should be camelized to FooBar'),
		              'Who\'s online' => array('Who\'s online', "WhoSOnline", '"Who\'s online" should be camelized to WhoSOnline'),
				      'AlreadyCam' => array('AlreadyCam', "Alreadycam", 'Already camelized word AlreadyCam should be squashed to Alreadycam'),
				      'CoWaBuNgA TEENAGE MutANt NiNjA turtles' => array('CoWaBuNgA TEENAGE MutANt NiNjA turtles', "CowabungaTeenageMutantNinjaTurtles", 'Mixed case with spaces should be converted to camel case'),
		);
	}
	
	/**
	 * test Camelized function
	 * @param string $word
	 * @param string $message
	 * @return void
	 * @dataProvider getTestCamelizeData
	 */
	public function testCamelize($word, $expect, $message)
	{
		$this->assertEquals(
				FOFInflector::camelize($word),
				$expect,
				$message
		);
	}
	
	public function getTestUnderscoreData()
	{
		return array( 'to be underscored' => array("to be underscored", "to_be_underscored", "Wrong transformation"),
				'toBeUnderscored' => array("toBeUnderscored", "to_be_underscored", "Wrong transformation"),
		);
	}
	
	/**
	 * test Underscore function
	 * @param string $word
	 * @param string $message
	 * @return void
	 * @dataProvider getTestUnderscoreData
	 */
	public function testUnderscore($word, $expect, $message)
	{
		$this->assertEquals(
				FOFInflector::underscore($word),
				$expect,
				$message
		);
	}
}

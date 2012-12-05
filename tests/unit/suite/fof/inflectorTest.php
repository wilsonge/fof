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
		              'words' => array("word", "words", "Should return the same as it's already a plural"),
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
}

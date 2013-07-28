<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2010 - 2013 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'modelDataprovider.php';

class FOFModelTest extends FtestCaseDatabase
{
	protected function setUp()
	{
		$loadDataset = true;
		$annotations = $this->getAnnotations();

		// Do I need a dataset for this set or not?
		if(isset($annotations['method']) && isset($annotations['method']['preventDataLoading']))
		{
			$loadDataset = false;
		}

		parent::setUp($loadDataset);
	}

	/**
	 * @group               modelIncludePath
	 * @covers              FOFModel::addIncludePath
	 * @dataProvider        getTestAddIncludePath
	 * @preventDataLoading
	 */
	public function testAddIncludePath($test, $check)
	{
		$model = FOFModel::getTmpInstance('Foobars', 'FOFModel');
		$model->addIncludePath($test['path'], $test['prefix']);

		$reflection = new ReflectionClass($model);
		$property   = $reflection->getProperty('paths');
		$property->setAccessible(true);

		$this->assertEquals($check['paths'], $property->getValue($model), '');
	}

	public function getTestAddIncludePath()
	{
		return ModelDataprovider::getTestAddIncludePath();
	}
}
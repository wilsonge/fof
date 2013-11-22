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

		// TODO It seems that another test is "polluting" FOFPlatform instance, leaving a Mock Object as current instance
		FOFPlatform::forceInstance(null);
	}

    /**
     * @group               modelTestGetId
     * @group               FOFModel
     * @covers              FOFModel::getId
     * @preventDataLoading
     */
    public function testGetId()
    {
        $config['option'] = 'com_foftest';

        $model = new FOFModel($config);

        // I prefer using the reflection class instead of the setter method, so I can be sure of what is going on
        $reflect  = new ReflectionClass($model);
        $property = $reflect->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($model, 88);

        $value = $model->getId();

        $this->assertEquals(88, $value, 'FOFModel::getId Wrong set value');
    }

	/**
	 * @group               modelTestSetId
	 * @group               FOFModel
	 * @covers              FOFModel::setId
	 * @dataProvider        getTestSetId
	 * @preventDataLoading
	 */
	public function testSetId($modelId)
	{
		$config['option'] = 'com_foftest';

		$model = new FOFModel($config);
		$rc = $model->setId($modelId);

		$this->assertInstanceOf('FOFModel', $rc, 'FOFModel::setId should return itself in order to support chaining');

		$reflect  = new ReflectionClass($model);
		$property = $reflect->getProperty('id');
		$property->setAccessible(true);
		$value    = $property->getValue($model);

        if(is_array($modelId))
        {
            $expected = array_shift($modelId);
        }
        else
        {
            $expected = $modelId;
        }

		$this->assertEquals($expected, $value, 'FOFModel::setId Wrong set value');
	}

	/**
	 * @group               modelTestSetIdException
	 * @group               FOFModel
	 * @covers              FOFModel::setId
	 * @dataProvider        getTestSetIdException
	 * @preventDataLoading
	 */
	public function testSetIdException($modelId)
	{
		$this->setExpectedException('InvalidArgumentException');

		$config['option'] = 'com_foftest';

		$model = new FOFModel($config);
		$model->setId($modelId);
	}

    /**
     * @group               modelTestSetIds
     * @group               FOFModel
     * @covers              FOFModel::setIds
     * @dataProvider        getTestSetIds
     * @preventDataLoading
     */
    public function testSetIds($modelIds, $check)
    {
        $config['option'] = 'com_foftest';

        $model = new FOFModel($config);
        $rc = $model->setIds($modelIds);

        $this->assertInstanceOf('FOFModel', $rc, 'FOFModel::setIds should return itself in order to support chaining');

        $reflect  = new ReflectionClass($model);

        $property = $reflect->getProperty('id');
        $property->setAccessible(true);
        $value    = $property->getValue($model);

        $this->assertEquals($check['id'], $value, 'FOFModel::setIds Wrong value for "id" property');

        $property = $reflect->getProperty('id_list');
        $property->setAccessible(true);
        $value    = $property->getValue($model);

        $this->assertEquals($check['id_list'], $value, 'FOFModel::setIds Wrong value for "id_list" property');
    }

    /**
     * @group               modelTestReset
     * @group               FOFModel
     * @covers              FOFModel::reset
     * @preventDataLoading
     */
    public function testReset()
    {
        $config['option'] = 'com_foftest';

        $model = new FOFModel($config);

        $toBeInspected = array(
            'id'         => array('expected' => 0),
            'id_list'    => array('expected' => null),
            'record'     => array('expected' => null),
            'list'       => array('expected' => null),
            'pagination' => array('expected' => null),
            'total'      => array('expected' => null),
            'otable'     => array('expected' => null)
        );

        $reflect  = new ReflectionClass($model);

        foreach($toBeInspected as $property => &$info)
        {
            $t = $reflect->getProperty($property);
            $t->setAccessible(true);

            $t->setValue($model, 'dummy value');

            $info['reflection'] = $t;
        }

        $model->reset();

        foreach($toBeInspected as $property => &$info)
        {
            $value = $info['reflection']->getValue($model);

            $this->assertEquals($info['expected'], $value, '');
        }
    }

	/**
	 * @group               modelIncludePath
	 * @covers              FOFModel::addIncludePath
	 * @dataProvider        getTestAddIncludePathException
	 * @preventDataLoading
	 */
	/*public function testAddIncludePath($test, $check)
	{
		$model = FOFModel::getTmpInstance('Foobars', 'FOFModel');
		$return = $model->addIncludePath($test['path'], $test['prefix']);

		$reflection = new ReflectionClass($model);
		$property   = $reflection->getProperty('paths');
		$property->setAccessible(true);
		$value = $property->getValue($model);

		if(is_string($test['path']))
		{
			$test['path'] = JPath::clean($test['path']);
		}
		elseif(is_array($test['path']))
		{
			$paths = array();

			foreach($test['path'] as $path)
			{
				$paths[] = JPath::clean($path);
			}

			$test['path'] = $paths;
		}

		$expected = array(
			'' => array($test['path']),
			$test['prefix'] => array($test['path'])
		);

		foreach($check['return'] as $path)
		{
			$cleaned[] = JPath::clean($path);
		}

		$this->assertEquals($cleaned, $return, 'AddIncludePath: wrong return value');
		$this->assertEquals($expected, $value, 'AddIncludePath: wrong assigned value');
	}*/

	public function getTestAddIncludePath()
	{
		return ModelDataprovider::getTestAddIncludePath();
	}

	public function getTestSetId()
	{
		return ModelDataprovider::getTestSetId();
	}

	public function getTestSetIdException()
	{
		return ModelDataprovider::getTestSetIdException();
	}

    public function getTestSetIds()
    {
        return ModelDataprovider::getTestSetIds();
    }
}
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
     * @group               modelTestSetIdsFromRequest
     * @group               FOFModel
     * @covers              FOFModel::setIDsFromRequest
     * @dataProvider        getTestSetIDsFromRequest
     * @preventDataLoading
     */
    public function testSetIDsFromRequest($modelinfo, $test, $checks)
    {
        $config['option'] = 'com_foftest';
        $config['view']   = $modelinfo['name'];
        $input            = array();

        if(isset($test['cid']))
        {
            $input['cid'] = $test['cid'];
        }

        if(isset($test['id']))
        {
            $input['id'] = $test['id'];
        }

        if(isset($test['kid']))
        {
            $input[$test['kid']['name']] = $test['kid']['value'];
        }

        // FOFModel constructor will automatically set the data coming from the request, taking the from the request
        // (which is bad for our test), so I have to use the getTmp method.
        // Sadly, it will reset the input, too, so I have to manually inject it.
        $model = FOFModel::getTmpInstance($modelinfo['name'], 'FoftestModel', $config);
        $model->setInput(new FOFInput($input));

        $model->setIDsFromRequest();

        $property = new ReflectionProperty($model, 'id');
        $property->setAccessible(true);
        $this->assertEquals($checks['id'], $property->getValue($model), 'FOFModel::setIDsFromRequests wrong value for property "id"');

        $property = new ReflectionProperty($model, 'id_list');
        $property->setAccessible(true);
        $this->assertEquals($checks['id_list'], $property->getValue($model), 'FOFModel::setIDsFromRequests wrong value for property "id_list"');
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
     * @group               modelTestGetItem
     * @group               FOFModel
     * @covers              FOFModel::getItem
     * @dataProvider        getTestGetItem
     */
    public function testGetItem($modelinfo, $test, $session, $checks)
    {
        $config['option'] = 'com_foftest';
        $config['view']   = $modelinfo['name'];
        $model = FOFModel::getTmpInstance($modelinfo['name'], 'FoftestModel', $config);

        if($test['setid'])
        {
            $model->setId($test['setid']);
        }

        if($session)
        {
            $mockSession = $this->getMock('JSession', array('get'));
            $mockSession->expects($this->any())->method('get')->will($this->returnValue($session));

            JFactory::$session = $mockSession;
        }

        $result = $model->getItem($test['id']);

        $this->assertInstanceOf('FOFTable', $result, 'FOFModel::getItem should return an instance of FOFTable');

        foreach($checks as $property => $value)
        {
            $this->assertEquals($value, $result->$property, 'FOFModel::getItem loaded the wrong data for property '.$property);
        }
    }

    /**
     * Tailored test to check when the session has a different id from the loaded from db one
     *
     * @group               modelTestGetItem
     * @group               FOFModel
     * @covers              FOFModel::getItem
     */
    public function testGetItemSessionWipe()
    {
        $config['option'] = 'com_foftest';
        $config['view']   = 'foobars';
        $model = FOFModel::getTmpInstance('Foobars', 'FoftestModel', $config);

        $hackedSession = new JSession;

        // Manually set the session as active
        $property = new ReflectionProperty($hackedSession, '_state');
        $property->setAccessible(true);
        $property->setValue($hackedSession, 'active');

        $session = serialize(array('foftest_foobar_id' => 0, 'title' => 'Title from session'));

        // We're in CLI an no $_SESSION variable? No problem, I'll manually create it!
        // I'm going to hell for doing this...
        $_SESSION['__default']['com_foftest.cpanels.savedata'] = $session;

        JFactory::$session = $hackedSession;

        $result = $model->getItem(2);

        $this->assertInstanceOf('FOFTable', $result, 'FOFModel::getItem should return an instance of FOFTable');
        $this->assertArrayNotHasKey('com_foftest.cpanels.savedata', $_SESSION['__default'], 'FOFModel::getItem should wipe saved session data');

        // Let's remove any evidence...
        unset($_SESSION);
    }

    /**
     * @group               modelTestBuildQuery
     * @group               FOFModel
     * @covers              FOFModel::buildQuery
     * @dataProvider        getTestBuildQuery
     * @preventDataLoading
     */
    public function testBuildQuery($modelinfo, $test, $checks)
    {
        $config['input']  = array(
            'option'    => 'com_foftest',
            'view'      => $modelinfo['name']
        );

        // Create a mock so I can test vs different table alias
        $model = $this->getMock('FOFModel', array('getTableAlias'), array($config), ucfirst($modelinfo['name']).'FoftestModel');
        $model->expects($this->any())->method('getTableAlias')->will($this->returnValue($test['aliasTable']));

        // Let's create a mocked Behavior, so I can manipulate its behavior (LOL)
        $behavior = $this->getMock('FOFModelDispatcherBehavior', array('trigger'));
        $behavior->expects($this->any())->method('trigger')->will($this->returnValue(null));

        // Inject the hacked behavior
        $property = new ReflectionProperty($model, 'modelDispatcher');
        $property->setAccessible(true);
        $property->setValue($model, $behavior);

        $query = $model->buildQuery($test['overrideLimits']);

        $this->assertEquals((string) $checks['query'], (string) $query, 'FOFModel::buildQuery returned a wrong query');
    }

    /**
     * @group               modelTestGetList
     * @group               FOFModel
     * @covers              FOFModel::_getList
     * @covers              FOFModel::onProcessList
     * @dataProvider        getTestGetList
     */
    public function testGetList($modelinfo, $test, $checks)
    {
        $config['input']  = array(
            'option'    => 'com_foftest',
            'view'      => $modelinfo['name']
        );

        // Create a mock so I can test onProcessList, too
        $model = $this->getMock('FOFModel', array('onProcessList'), array($config));
        $model->expects($this->any())->method('onProcessList')->will($this->returnCallback($test['callback']));

        $method = new ReflectionMethod($model, '_getList');
        $method->setAccessible(true);
        $list = $method->invoke($model, $test['query'], $test['limitstart'], $test['limit'], $test['group']);

        $this->assertEquals($checks['list'], $list, 'FOFModel::_getList returned a wrong recordset');
    }

    /**
     * @group               modelTestGetItemList
     * @group               FOFModel
     * @covers              FOFModel::getItemList
     * @dataProvider        getTestGetItemList
     */
    public function testGetItemList($modelinfo, $test, $checks)
    {
        $config['input']  = array(
            'option'    => 'com_foftest',
            'view'      => $modelinfo['name']
        );

        $model = $this->getMock('FOFModel', array('buildQuery'), array($config));

        $model->expects($this->any())->method('buildQuery')->will($this->returnValue($test['query']));
        $model->limit($test['limit']);
        $model->limitstart($test['limitstart']);

        $list = $model->getItemList($test['override'], $test['group']);

        $this->assertEquals($checks['list'], $list, 'FOFModel::getItemList return a wrong recordset');
    }

    /**
     * Tailored test to check if getItemList is using the internal cache, instead of running the query again
     *
     * @group               modelTestGetItemListCache
     * @group               FOFModel
     * @covers              FOFModel::getItemList
     * @dataProvider        getTestGetItemList
     * @preventDataLoading
     */
    public function testGetItemListCache()
    {
        $config['option'] = 'com_foftest';
        $config['view']   = 'foobars';
        $model = FOFModel::getTmpInstance('Foobars', 'FoftestModel', $config);

        $dummy = array('Hijacked internal cache');

        $property = new ReflectionProperty($model, 'list');
        $property->setAccessible(true);
        $property->setValue($model, $dummy);

        $result = $model->getItemList();

        $this->assertEquals($dummy, $result, 'FOFModel::getItemList failed to use its internal cache');
    }

    /**
     * In this test I will simply check that the invocation of the _createTable is made with the correct
     * arguments. I will check for the correct table to be returned while testing _createTable.
     *
     * @group               modelTestGetTable
     * @group               FOFModel
     * @covers              FOFModel::getTable
     * @dataProvider        getTestGetTable
     * @preventDataLoading
     */
    public function testGetTable($modelinfo, $test)
    {
        // This is a workaround for dealing with mocked objects. When checking the arguments passed to _createTable
        // the return value is NULL, and this throws an exception. However, this is the expected behavior. We can't simply
        // wrap everything with a try-catch statement since it will prevent PHPUnit from notifing us of errors
        $this->setExpectedException('Exception', 0);

        $config['input']  = array(
            'option'    => 'com_foftest',
            'view'      => $modelinfo['name']
        );

        $model = $this->getMock('FOFModel', array('_createTable'), array($config), ucfirst($modelinfo['name']));

        if(!$test['create']['options'])
        {
            $reflection = new ReflectionProperty($model, 'input');
            $reflection->setAccessible(true);

            $test['create']['options'] = array('input' => $reflection->getValue($model));
        }

        if(isset($test['wipeTable']))
        {
            $reflection = new ReflectionProperty($model, 'table');
            $reflection->setAccessible(true);
            $reflection->setValue($model, null);
        }

        $model->expects($this->any())
              ->method('_createTable')
              ->with(
                $test['create']['name'],
                $test['create']['prefix'],
                $test['create']['options']
            );

        $table = $model->getTable($test['name'], $test['prefix'], $test['options']);
    }

    /**
     * @group               modelTestGetTable
     * @group               FOFModel
     * @covers              FOFModel::getTable
     * @preventDataLoading
     */
    public function testGetTableException()
    {
        $this->setExpectedException('Exception', 0);

        $config['input']  = array(
            'option'    => 'com_foftest',
            'view'      => 'foobars'
        );

        $model = $this->getMock('FOFModel', array('_createTable'), array($config));
        $model->expects($this->any())->method('_createTable')->will($this->returnValue(false));

        $model->getTable();
    }

    public function getTestSetIDsFromRequest()
    {
        return ModelDataprovider::getTestSetIDsFromRequest();
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

    public function getTestGetItem()
    {
        return ModelDataprovider::getTestGetItem();
    }

    public function getTestBuildQuery()
    {
        return ModelDataprovider::getTestBuildQuery();
    }

    public function getTestGetList()
    {
        return ModelDataprovider::getTestGetList();
    }

    public function getTestGetItemList()
    {
        return ModelDataprovider::getTestGetItemList();
    }

    public function getTestGetTable()
    {
        return ModelDataprovider::getTestGetTable();
    }
}
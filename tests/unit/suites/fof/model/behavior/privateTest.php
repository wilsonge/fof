<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  TableBehaviors
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'privateDataprovider.php';

class FOFModelBehaviorPrivateTest extends FtestCaseDatabase
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

        FOFPlatform::forceInstance(null);
        FOFTable::forceInstance(null);
    }

    /**
     * @group               privateOnAfterBuildQuery
     * @group               FOFModelBehavior
     * @covers              FOFModelBehaviorPrivate::onAfterBuildQuery
     * @dataProvider        getTestOnAfterBuildQuery
     */
    public function testOnAfterBuildQuery($modelinfo, $test, $checks)
    {
        $config['option'] = 'com_foftest';
        $config['name']   = $modelinfo['name'];
        $config['table']  = FOFInflector::singularize($modelinfo['name']);
        $config['input']  = array('option' => 'com_foftest', 'view' => $modelinfo['name']);

        $model = $this->getMock('FOFModel', array('getTable'), array($config));

        $user = (object) array('id' => $test['user']);
        $platform = $this->getMock('FOFIntegrationJoomlaPlatform', array('isFrontend', 'getUser'));
        $platform->expects($this->any())->method('isFrontend')->will($this->returnValue($test['frontend']));
        $platform->expects($this->any())->method('getUser')->will($this->returnValue($user));

        FOFPlatform::forceInstance($platform);

        $reflection = new ReflectionProperty($model, 'modelDispatcher');
        $reflection->setAccessible(true);
        $dispatcher = $reflection->getValue($model);

        $behavior = new FOFModelBehaviorPrivate($dispatcher);

        $table = FOFTable::getAnInstance(ucfirst(FOFInflector::singularize($modelinfo['name'])), 'FoftestTable');

        if(isset($test['aliases']))
        {
            foreach($test['aliases'] as $column => $alias)
            {
                $table->setColumnAlias($column, $alias);
            }
        }

        if(isset($test['table_alias']))
        {
            $table->setTableAlias($test['table_alias']);
        }

        $model->expects($this->any())->method('getTable')->will($this->returnValue($table));

        $behavior->onAfterBuildQuery($model, $test['query']);

        $this->assertEquals((string)$checks['query'], (string)$test['query'], 'FOFModelBehaviorPrivate::onAfterBuildQuery failed to modify the query object');
    }

    /**
     * @group               privateOnAfterGetItem
     * @group               FOFModelBehavior
     * @covers              FOFModelBehaviorPrivate::onAfterGetItem
     * @dataProvider        getTestOnAfterGetItem
     */
    public function testOnAfterGetItem($modelinfo, $test, $checks)
    {
        $config = array();

        if(isset($test['aliases']['tbl_key']))
        {
            $config['tbl_key'] = $test['aliases']['tbl_key'];
        }

        $user = (object) array('id' => $test['user']);
        $platform = $this->getMock('FOFIntegrationJoomlaPlatform', array('getUser'));
        $platform->expects($this->any())->method('getUser')->will($this->returnValue($user));

        FOFPlatform::forceInstance($platform);

        $model = FOFModel::getTmpInstance($modelinfo['name'], 'FoftestModel');
        $table = FOFTable::getAnInstance(ucfirst(FOFInflector::singularize($modelinfo['name'])), 'FoftestTable', $config);

        if(isset($test['aliases']))
        {
            foreach($test['aliases'] as $column => $alias)
            {
                $table->setColumnAlias($column, $alias);
            }
        }

        $reflection = new ReflectionProperty($model, 'modelDispatcher');
        $reflection->setAccessible(true);
        $dispatcher = $reflection->getValue($model);

        $behavior = new FOFModelBehaviorPrivate($dispatcher);

        if(isset($test['loadid']))
        {
            $table->load($test['loadid']);
        }

        $saved = clone $table;
        $behavior->onAfterGetItem($model, $table);

        if($checks['nullify'])
        {
            $this->assertNull($table, "FOFModelBehaviorPrivate::onAfterGetItem should nullify the table record when it's not enabled");
        }
        else
        {
            $this->assertEquals($saved, $table, "FOFModelBehaviorPrivate::onAfterGetItem should leave the record untouched if it's enabled");
        }
    }

    public function getTestOnAfterBuildQuery()
    {
        return privateDataprovider::getTestOnAfterBuildQuery();
    }

    public function getTestOnAfterGetItem()
    {
        return privateDataprovider::getTestOnAfterGetItem();
    }
}

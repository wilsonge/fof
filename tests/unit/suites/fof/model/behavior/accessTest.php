<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  TableBehaviors
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'accessDataprovider.php';

class FOFModelBehaviorAccessTest extends FtestCaseDatabase
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
     * @group               accessOnAfterBuildQuery
     * @group               FOFModelBehavior
     * @covers              FOFModelBehaviorAccess::onAfterBuildQuery
     * @dataProvider        getTestOnAfterBuildQuery
     */
    public function testOnAfterBuildQuery($modelinfo, $test, $checks)
    {
        $config['option'] = 'com_foftest';
        $config['name']   = $modelinfo['name'];
        $config['table']  = FOFInflector::singularize($modelinfo['name']);
        $config['input']  = array('option' => 'com_foftest', 'view' => $modelinfo['name']);

        $model = $this->getMock('FOFModel', array('applyAccessFiltering', 'getTable'), array($config));

        $platform = $this->getMock('FOFPlatformJoomlaPlatform', array('isFrontend'));
        $platform->expects($this->any())->method('isFrontend')->will($this->returnValue($test['frontend']));

        FOFPlatform::forceInstance($platform);

        $reflection = new ReflectionProperty($model, 'modelDispatcher');
        $reflection->setAccessible(true);
        $dispatcher = $reflection->getValue($model);

        $behavior = new FOFModelBehaviorAccess($dispatcher);

        $table = FOFTable::getAnInstance(ucfirst(FOFInflector::singularize($modelinfo['name'])), 'FoftestTable');

        if(isset($test['aliases']))
        {
            foreach($test['aliases'] as $column => $alias)
            {
                $table->setColumnAlias($column, $alias);
            }
        }

        $model->expects($this->any())->method('getTable')->will($this->returnValue($table));

        if($checks['execute'])
        {
            $model->expects($this->once())->method('applyAccessFiltering')->with(null);
        }
        else
        {
            $model->expects($this->never())->method('applyAccessFiltering');
        }

        $null  = null;
        $behavior->onAfterBuildQuery($model, $null);
    }

    public function getTestOnAfterBuildQuery()
    {
        return accessDataprovider::getTestOnAfterBuildQuery();
    }
}

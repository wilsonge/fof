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
    public function testOnAfterBuildQuery($modelinfo, $test)
    {
        $config['option'] = 'com_foftest';
        $config['name']   = $modelinfo['name'];
        $config['table']  = FOFInflector::singularize($modelinfo['name']);
        $config['input']  = array('option' => 'com_foftest', 'view' => $modelinfo['name']);

        $model = $this->getMock('FOFModel', array('applyAccessFiltering'), array($config));

        $platform = $this->getMock('FOFPlatformJoomlaPlatform', array('isFrontend'));
        $platform->expects($this->any())->method('isFrontend')->will($this->returnValue($test['frontend']));

        FOFPlatform::forceInstance($platform);

        $reflection = new ReflectionProperty($model, 'modelDispatcher');
        $reflection->setAccessible(true);
        $dispatcher = $reflection->getValue($model);

        $behavior = new FOFModelBehaviorAccess($dispatcher);

        $table = $model->getTable();

        if($test['frontend'] && in_array($table->getColumnAlias('access'), $table->getKnownFields()))
        {
            $model->expects($this->any())->method('applyAccessFiltering')->with(null);
        }

        $behavior->onAfterBuildQuery($model, $test['query']);
    }

    /**
     * @group               assetsOnAfterBind
     * @group               FOFTableBehavior
     * @covers              FOFTableBehaviorAssets::onAfterBind
     * @dataProvider        getTestOnAfterBind
     */
    public function testOnAfterBind($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['name']));

        if(isset($test['tbl_key']))
        {
            $config['tbl_key'] = $test['tbl_key'];
        }

        $table = FOFTable::getAnInstance($tableinfo['name'], 'FoftestTable', $config);

        $reflection = new ReflectionProperty($table, 'tableDispatcher');
        $reflection->setAccessible(true);
        $dispatcher = $reflection->getValue($table);

        $behavior = new FOFTableBehaviorAssets($dispatcher);

        if(isset($test['alias']))
        {
            foreach($test['alias'] as $column => $alias)
            {
                $table->setColumnAlias($column, $alias);
            }

            $table->setAssetsTracked(true);
        }

        if(isset($test['id']))
        {
            $table->load($test['id']);
        }

        $return = $behavior->onAfterBind($table, $test['bind']);
        $rules  = $table->getRules();

        $this->assertEquals($check['return'], $return, 'FOFTableBehaviorAssets::onAfterStore returned a wrong value');
        $this->assertJsonStringEqualsJsonString($check['rules'], (string) $rules, 'FOFTableBehaviorAssets::onAfterStore set rules wrong');
    }

    public function getTestOnAfterBuildQuery()
    {
        return accessDataprovider::getTestOnAfterBuildQuery();
    }
}

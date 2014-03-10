<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'relationsDataprovider.php';
//require_once JPATH_TESTS.'/unit/core/table/table.php';

class FOFTableRelationsTest extends FtestCaseDatabase
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
     * Let's check if FOFTableReations constructor detects all the parent table
     *
     * @group               relationsConstruct
     * @group               FOFTableRelations
     * @dataProvider        getTest__construct
     * @covers              FOFTableRelations::__construct
     */
    public function test__construct($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations = $relations->getValue($relation);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation = $defaultRelation->getValue($relation);

        // The table is supposed to have a parent, let's check if it was detected
        if($check['hasParent'])
        {
            $this->assertArrayHasKey($check['relation']['key'], $relations['parent'], 'FOFTableRelations failed to reconize and store the parent relation');
            $this->assertEquals($check['relation']['content'], $relations['parent'][$check['relation']['key']], 'FOFTableRelations stored the wrong info for this parent relation');
            $this->assertEquals($check['relation']['key'], $defaultRelation['parent'], 'FOFTableRelation failed to store the parent relation as the default one');
        }
        else
        {
            $this->assertArrayNotHasKey($check['relation']['key'], $relations['parent'], 'FOFTableRelations failed to reconize and store the parent relation');
            $this->assertEquals(array(), $relations['parent'], 'FOFTableRelations should contain no parent relation');
        }

    }

    public static function getTest__construct()
    {
        return RelationsDataprovider::getTest__construct();
    }
}
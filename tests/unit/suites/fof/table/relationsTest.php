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
     * Let's check if FOFTableReations constructor detects the parent table relation
     *
     * @group               relationsConstruct
     * @group               FOFTableRelations
     * @dataProvider        getTest__construct
     * @covers              FOFTableRelations::__construct
     */
    public function test__construct($tableinfo, $check)
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

    /**
     * @group               relationsAddChildRelation
     * @group               FOFTableRelations
     * @dataProvider        getTestAddChildRelation
     * @covers              FOFTableRelations::addChildRelation
     */
    public function testAddChildRelation($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relation->addChildRelation(
            $test['relation']['itemName'],
            $test['relation']['tableClass'],
            $test['relation']['localKey'],
            $test['relation']['remoteKey'],
            $test['relation']['default']
        );

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations = $relations->getValue($relation);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation = $defaultRelation->getValue($relation);

        $this->assertEquals($check['relation']['content'], $relations['child'][$check['relation']['key']], 'FOFTableRelations stored the wrong info for this child relation');
        $this->assertEquals($check['relation']['default'], $defaultRelation['child'], 'FOFTableRelation default relation not stored as expected');
    }

    /**
     * @group               relationsAddParentRelation
     * @group               FOFTableRelations
     * @dataProvider        getTestAddParentRelation
     * @covers              FOFTableRelations::addParentRelation
     */
    public function testAddParentRelation($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        // To test it properly, I have to clear the relation automatically created by the constructor
        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, array(
                'child'		=> array(),
                'parent'	=> array(),
                'children'	=> array(),
                'multiple'	=> array()
            )
        );

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation->setValue($relation, array(
                'child'		=> null,
                'parent'	=> null,
                'children'	=> null,
                'multiple'	=> null,
            )
        );

        $relation->addParentRelation(
            $test['relation']['itemName'],
            $test['relation']['tableClass'],
            $test['relation']['localKey'],
            $test['relation']['remoteKey'],
            $test['relation']['default']
        );

        $relations       = $relations->getValue($relation);
        $defaultRelation = $defaultRelation->getValue($relation);

        $this->assertEquals($check['relation']['content'], $relations['parent'][$check['relation']['key']], 'FOFTableRelations stored the wrong info for this parent relation');
        $this->assertEquals($check['relation']['default'], $defaultRelation['parent'], 'FOFTableRelation default relation not stored as expected');
    }

    /**
     * @group               relationsAddChildrenRelation
     * @group               FOFTableRelations
     * @dataProvider        getTestAddChildrenRelation
     * @covers              FOFTableRelations::addChildrenRelation
     */
    public function testAddChildrenRelation($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relation->addChildrenRelation(
            $test['relation']['itemName'],
            $test['relation']['tableClass'],
            $test['relation']['localKey'],
            $test['relation']['remoteKey'],
            $test['relation']['default']
        );

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations = $relations->getValue($relation);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation = $defaultRelation->getValue($relation);

        $this->assertEquals($check['relation']['content'], $relations['children'][$check['relation']['key']], 'FOFTableRelations stored the wrong info for children relation');
        $this->assertEquals($check['relation']['default'], $defaultRelation['children'], 'FOFTableRelation default relation not stored as expected');
    }

    public static function getTest__construct()
    {
        return RelationsDataprovider::getTest__construct();
    }

    public static function getTestAddChildRelation()
    {
        return RelationsDataprovider::getTestAddChildRelation();
    }

    public static function getTestAddParentRelation()
    {
        return RelationsDataprovider::getTestAddParentRelation();
    }

    public static function getTestAddChildrenRelation()
    {
        return RelationsDataprovider::getTestAddChildrenRelation();
    }
}
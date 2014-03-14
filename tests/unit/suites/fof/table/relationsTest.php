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

    /**
     * @group               relationsAddMultipleRelation
     * @group               FOFTableRelations
     * @dataProvider        getTestAddMultipleRelation
     * @covers              FOFTableRelations::addMultipleRelation
     */
    public function testAddMultipleRelation($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relation->addMultipleRelation(
            $test['relation']['itemName'],
            $test['relation']['tableClass'],
            $test['relation']['localKey'],
            $test['relation']['ourPivot'],
            $test['relation']['theirPivot'],
            $test['relation']['remoteKey'],
            $test['relation']['glueTable'],
            $test['relation']['default']
        );

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations = $relations->getValue($relation);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation = $defaultRelation->getValue($relation);

        $this->assertEquals($check['relation']['content'], $relations['multiple'][$check['relation']['key']], 'FOFTableRelations stored the wrong info for multiple relation');
        $this->assertEquals($check['relation']['default'], $defaultRelation['multiple'], 'FOFTableRelation default relation not stored as expected');
    }

    /**
     * @group               relationsRemoveRelation
     * @group               FOFTableRelations
     * @dataProvider        getTestRemoveRelation
     * @covers              FOFTableRelations::removeRelation
     */
    public function testRemoveRelation($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        // Do I have a default relation?
        if(isset($test['default']))
        {
            $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
            $defaultRelation->setAccessible(true);
            $defaultRelation->setValue($relation, $test['default']);
        }

        $relation->removeRelation($test['itemName'], $test['type']);

        $relations = $relations->getValue($relation);

        $this->assertEquals($check['relations'], $relations, 'FOFTableRelations::removeRelation failed to remove the relation(s)');

        if(isset($check['default']))
        {
            $defaultRelation = $defaultRelation->getValue($relation);

            $this->assertEquals($check['default'], $defaultRelation, 'FOFTableRelations::removeRelation failed to remove the default relation');
        }
    }

    /**
     * @group               relationsClearRelations
     * @group               FOFTableRelations
     * @dataProvider        getTestClearRelations
     * @covers              FOFTableRelations::clearRelations
     */
    public function testClearRelations($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        // Do I have a default relation?
        if(isset($test['default']))
        {
            $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
            $defaultRelation->setAccessible(true);
            $defaultRelation->setValue($relation, $test['default']);
        }

        $relation->clearRelations($test['type']);

        $relations = $relations->getValue($relation);

        $this->assertEquals($check['relations'], $relations, 'FOFTableRelations::removeRelation failed to remove the relation(s)');

        if(isset($check['default']))
        {
            $defaultRelation = $defaultRelation->getValue($relation);

            $this->assertEquals($check['default'], $defaultRelation, 'FOFTableRelations::removeRelation failed to remove the default relation');
        }
    }

    /**
     * @group               relationsHasRelation
     * @group               FOFTableRelations
     * @dataProvider        getTestHasRelation
     * @covers              FOFTableRelations::hasRelation
     */
    public function testHasRelation($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $result = $relation->hasRelation($test['itemName'], $test['type']);

        $this->assertEquals($check['result'], $result, 'FOFTableRelations::hasRelation failed to detect the relation');

    }

    /**
     * @group               relationsGetRelation
     * @group               FOFTableRelations
     * @dataProvider        getTestGetRelation
     * @covers              FOFTableRelations::getRelation
     */
    public function testGetRelation($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $result = $relation->getRelation($test['itemName'], $test['type']);

        $this->assertArrayHasKey('type', $result, 'FOFTableRelations::getRelation should set the "type" key');
    }

    /**
     * @group               relationsGetRelatedItem
     * @group               FOFTableRelations
     * @dataProvider        getTestGetRelatedItem
     * @covers              FOFTableRelations::getRelatedItem
     */
    public function testGetRelatedItem($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('FOFTableRelations', array('getParent', 'getChild'), array($table));
        $relation->expects($this->any())->method('getParent')->will($this->returnValue(true));
        $relation->expects($this->any())->method('getChild')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $relation->getRelatedItem($test['itemName'], $test['type']);
    }

    /**
     * @group               relationsGetRelatedItems
     * @group               FOFTableRelations
     * @dataProvider        getTestGetRelatedItems
     * @covers              FOFTableRelations::getRelatedItems
     */
    public function testGetRelatedItems($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('FOFTableRelations', array('getChildren', 'getMultiple', 'getSiblings'), array($table));
        $relation->expects($this->any())->method('getChildren')->will($this->returnValue(true));
        $relation->expects($this->any())->method('getMultiple')->will($this->returnValue(true));
        $relation->expects($this->any())->method('getSiblings')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $relation->getRelatedItems($test['itemName'], $test['type']);
    }

    /**
     * @group               relationsGetParent
     * @group               FOFTableRelations
     * @dataProvider        getTestGetParent
     * @covers              FOFTableRelations::getParent
     */
    public function testGetParent($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('FOFTableRelations', array('getTableFromRelation'), array($table));
        $relation->expects($this->any())->method('getTableFromRelation')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation->setValue($relation, $test['default']);

        $relation->getParent($test['itemName']);
    }

    /**
     * @group               relationsGetChild
     * @group               FOFTableRelations
     * @dataProvider        getTestGetChild
     * @covers              FOFTableRelations::getChild
     */
    public function testGetChild($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('FOFTableRelations', array('getTableFromRelation'), array($table));
        $relation->expects($this->any())->method('getTableFromRelation')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation->setValue($relation, $test['default']);

        $relation->getChild($test['itemName']);
    }

    /**
     * @group               relationsGetChildren
     * @group               FOFTableRelations
     * @dataProvider        getTestGetChildren
     * @covers              FOFTableRelations::getChildren
     */
    public function testGetChildren($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('FOFTableRelations', array('getIteratorFromRelation'), array($table));
        $relation->expects($this->any())->method('getIteratorFromRelation')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation->setValue($relation, $test['default']);

        $relation->getChildren($test['itemName']);
    }

    /**
     * @group               relationsGetSiblings
     * @group               FOFTableRelations
     * @dataProvider        getTestGetSiblings
     * @covers              FOFTableRelations::getSiblings
     */
    public function testGetSiblings($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('FOFTableRelations', array('getIteratorFromRelation'), array($table));
        $relation->expects($this->any())->method('getIteratorFromRelation')->with($this->equalTo($check['iterator']));
        $relation->expects($this->any())->method('getIteratorFromRelation')->will($this->returnValue(true));

        $relation->getSiblings($test['itemName']);
    }

    /**
     * @group               relationsGetMultiple
     * @group               FOFTableRelations
     * @dataProvider        getTestGetMultiple
     * @covers              FOFTableRelations::getMultiple
     */
    public function testGetMultiple($tableinfo, $test, $check)
    {
        if(!$check['result'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $this->getMock('FOFTableRelations', array('getIteratorFromRelation'), array($table));
        $relation->expects($this->any())->method('getIteratorFromRelation')->will($this->returnValue(true));

        $relations = new ReflectionProperty($relation, 'relations');
        $relations->setAccessible(true);
        $relations->setValue($relation, $test['relations']);

        $defaultRelation = new ReflectionProperty($relation, 'defaultRelation');
        $defaultRelation->setAccessible(true);
        $defaultRelation->setValue($relation, $test['default']);

        $relation->getMultiple($test['itemName']);
    }


    /**
     * @group               relationsGetTableFromRelation
     * @group               FOFTableRelations
     * @dataProvider        getTestGetTableFromRelation
     * @covers              FOFTableRelations::getTableFromRelation
     */
    public function testGetTableFromRelation($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $table->load($test['loadid']);

        $relation = $table->getRelations();

        $getTable = new ReflectionMethod($relation, 'getTableFromRelation');
        $getTable->setAccessible(true);

        $relatedTable = $getTable->invoke($relation, $test['relation']);

        $pk = $relatedTable->getKeyName();

        $this->assertInstanceOf('FOFTable', $relatedTable, 'FOFTableRelations::getTableFromRelation should return an instance of the FOFTable');
        $this->assertEquals($check['id'], $relatedTable->$pk, 'FOFTableRelations::getTableFromRelation loaded the wrong linked table');
    }

    /**
     * @group               relationsGetTableFromRelationNoLoad
     * @group               FOFTableRelations
     * @covers              FOFTableRelations::getTableFromRelation
     */
    public function testGetTableFromRelationNoLoad()
    {
        $this->setExpectedException('RuntimeException');

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'child'));
        $table 		     = FOFTable::getAnInstance('child', 'FoftestTable', $config);

        $relationArg = array(
            'tableClass' => 'FoftestTableParent',
            'localKey'   => 'foftest_parent_id',
            'remoteKey'  => 'foftest_parent_id'
        );

        $relation = $table->getRelations();

        $getTable = new ReflectionMethod($relation, 'getTableFromRelation');
        $getTable->setAccessible(true);

        $getTable->invoke($relation, $relationArg);
    }

    /**
     * @group               relationsGetTableFromRelationInvalidArgs
     * @group               FOFTableRelations
     * @dataProvider        getTestGetTableFromRelationInvalidArgs
     * @covers              FOFTableRelations::getTableFromRelation
     */
    public function testGetTableFromRelationInvalidArgs($tableinfo, $test)
    {
        $this->setExpectedException('InvalidArgumentException');

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $getTable = new ReflectionMethod($relation, 'getTableFromRelation');
        $getTable->setAccessible(true);

        $getTable->invoke($relation, $test['relation']);
    }

    /**
     * @group               relationsGetIteratorFromRelation
     * @group               FOFTableRelations
     * @dataProvider        getTestGetIteratorFromRelation
     * @covers              FOFTableRelations::getIteratorFromRelation
     */
    public function testGetIteratorFromRelation($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $table->load($test['loadid']);

        $relation = $table->getRelations();

        $getTable = new ReflectionMethod($relation, 'getIteratorFromRelation');
        $getTable->setAccessible(true);

        $items = $getTable->invoke($relation, $test['relation']);

        $this->assertInstanceOf('FOFDatabaseIterator', $items, 'FOFTableRelations::getIteratorFromRelation should return an instance of the FOFDatabaseIterator');
        $this->assertEquals($check['count'], count($items), 'FOFTableRelations::getIteratorFromRelation returned the wrong number of items');
    }

    /**
     * @group               relationsGetIteratorFromRelationNoLoad
     * @group               FOFTableRelations
     * @covers              FOFTableRelations::getIteratorFromRelation
     */
    public function testGetIteratorFromRelationNoLoad()
    {
        $this->setExpectedException('RuntimeException');

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'parent'));
        $table 		     = FOFTable::getAnInstance('child', 'FoftestTable', $config);

        $relationArg = array(
            'tableClass' => 'FoftestTableChild',
            'localKey'   => 'foftest_parent_id',
            'remoteKey'  => 'foftest_parent_id'
        );

        $relation = $table->getRelations();

        $getTable = new ReflectionMethod($relation, 'getIteratorFromRelation');
        $getTable->setAccessible(true);

        $getTable->invoke($relation, $relationArg);
    }

    /**
     * @group               relationsGetIteratorFromRelationInvalidArgs
     * @group               FOFTableRelations
     * @dataProvider        getTestGetIteratorFromRelationInvalidArgs
     * @covers              FOFTableRelations::getIteratorFromRelation
     */
    public function testGetIteratorFromRelationInvalidArgs($tableinfo, $test)
    {
        $this->setExpectedException('InvalidArgumentException');

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $getTable = new ReflectionMethod($relation, 'getIteratorFromRelation');
        $getTable->setAccessible(true);

        $getTable->invoke($relation, $test['relation']);
    }

    /**
     * @group               relationsNormaliseItemName
     * @group               FOFTableRelations
     * @dataProvider        getTestNormaliseItemName
     * @covers              FOFTableRelations::normaliseItemName
     */
    public function testNormaliseItemName($tableinfo, $test, $check)
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $tableinfo['table']));
        $table 		     = FOFTable::getAnInstance($tableinfo['table'], 'FoftestTable', $config);

        $relation = $table->getRelations();

        $getTable = new ReflectionMethod($relation, 'normaliseItemName');
        $getTable->setAccessible(true);

        $itemname = $getTable->invoke($relation, $test['itemName'], $test['plural']);

        $this->assertEquals($check['itemname'], $itemname, 'FOFTableRelations::normaliseItemName created a wrong itemname string');
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

    public static function getTestAddMultipleRelation()
    {
        return RelationsDataprovider::getTestAddMultipleRelation();
    }

    public static function getTestRemoveRelation()
    {
        return RelationsDataprovider::getTestRemoveRelation();
    }

    public static function getTestClearRelations()
    {
        return RelationsDataprovider::getTestClearRelations();
    }

    public static function getTestHasRelation()
    {
        return RelationsDataprovider::getTestHasRelation();
    }

    public static function getTestGetRelation()
    {
        return RelationsDataprovider::getTestGetRelation();
    }

    public static function getTestGetRelatedItem()
    {
        return RelationsDataprovider::getTestGetRelatedItem();
    }

    public static function getTestGetRelatedItems()
    {
        return RelationsDataprovider::getTestGetRelatedItems();
    }

    public static function getTestGetParent()
    {
        return RelationsDataprovider::getTestGetParent();
    }

    public static function getTestGetChild()
    {
        return RelationsDataprovider::getTestGetChild();
    }

    public static function getTestGetChildren()
    {
        return RelationsDataprovider::getTestGetChildren();
    }

    public static function getTestGetSiblings()
    {
        return RelationsDataprovider::getTestGetSiblings();
    }

    public static function getTestGetMultiple()
    {
        return RelationsDataprovider::getTestGetMultiple();
    }

    public static function getTestGetTableFromRelation()
    {
        return RelationsDataprovider::getTestGetTableFromRelation();
    }

    public static function getTestGetTableFromRelationInvalidArgs()
    {
        return RelationsDataprovider::getTestGetTableFromRelationInvalidArgs();
    }

    public static function getTestGetIteratorFromRelation()
    {
        return RelationsDataprovider::getTestGetIteratorFromRelation();
    }

    public static function getTestGetIteratorFromRelationInvalidArgs()
    {
        return RelationsDataprovider::getTestGetIteratorFromRelationInvalidArgs();
    }

    public static function getTestNormaliseItemName()
    {
        return RelationsDataprovider::getTestNormaliseItemName();
    }
}
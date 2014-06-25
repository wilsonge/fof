<?php
use \Mockery as m;

/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_TESTS.'/unit/core/table/nested.php';
require_once 'nestedDataprovider.php';

/*
 * In these tests, in order to avoid countless queries, we use hardcoded values to check the results.
 * This means that if you ever change the data for nested sets, you'll have to double check these tests, since most likely they will break
 */
class F0FTableNestedTest extends FtestCaseDatabase
{
    protected function setUp()
    {
        $loadDataset = true;
        $annotations = $this->getAnnotations();

        // Do I need a dataset for this set or not?
        if (isset($annotations['method']) && isset($annotations['method']['preventDataLoading'])) {
            $loadDataset = false;
        }

        parent::setUp($loadDataset);

        F0FPlatform::forceInstance(null);
        F0FTable::forceInstance(null);
    }

    protected function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /**
     * @group               nested__contruct
     * @group               F0FTableNested
     * @covers              F0FTableNested::__construct
     * @dataProvider        NestedDataprovider::getTest__construct
     * @preventDataLoading
     */
    public function test__construct($test, $check)
    {
        if($check['exception'])
        {
            $this->setExpectedException('RuntimeException');
        }

        $db = JFactory::getDbo();

        new F0FTableNested($test['table'], $test['id'], $db);
    }

    /**
     * @group               nestedTestCheck
     * @group               F0FTableNested
     * @covers              F0FTableNested::check
     * @dataProvider        NestedDataprovider::getTestCheck
     * @preventDataLoading
     */
    public function testCheck($test, $check)
    {
        $db = JFactory::getDbo();

        $table = $this->getMock('F0FTableNested', array('resetTreeCache'), array($test['table'], $test['id'], &$db));
        $table->expects($this->any())->method('resetTreeCache')->willReturn($this->returnValue(null));

        foreach($test['fields'] as $field => $value)
        {
            $table->$field = $value;
        }

        $return = $table->check();

        $this->assertEquals($check['return'], $return, 'F0FTableNested::check returned the wrong value');

        foreach($check['fields'] as $field => $expected)
        {
            if(is_null($expected))
            {
                $this->assertObjectNotHasAttribute($field, $table, 'F0FTableNested::check set the field '.$field.' even if it should not');
            }
            else
            {
                $this->assertEquals($expected, $table->$field, 'F0FTableNested::check failed to set the field '.$field);
            }
        }
    }

    /**
     * @group               nestedTestDelete
     * @group               F0FTableNested
     * @covers              F0FTableNested::delete
     * @dataProvider        NestedDataprovider::getTestDelete
     */
    public function testDelete($test, $check)
    {
        $db = JFactory::getDbo();

        $table = F0FTable::getAnInstance('Nestedset', 'FoftestTable');

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        $return = $table->delete($test['delete'], $test['recursive']);

        $this->assertEquals($check['return'], $return, 'F0FTableNested::delete returned the wrong value');

        $query = $db->getQuery(true)->select($table->getKeyName())->from($table->getTableName());
        $items = $db->setQuery($query)->loadColumn();

        $this->assertEmpty(array_intersect($check['deleted'], $items), 'F0FTableNested::delete failed to delete all the items');
    }

    /**
     * @group               nestedTestReorder
     * @group               F0FTableNested
     * @covers              F0FTableNested::reorder
     * @preventDataLoading
     */
    public function testReorder()
    {
        $this->setExpectedException('RuntimeException');

        $db = JFactory::getDbo();

        $table = new F0FTableNested('#__foftest_nestedsets', 'id', $db);
        $table->reorder();
    }

    /**
     * @group               nestedTestMove
     * @group               F0FTableNested
     * @covers              F0FTableNested::move
     * @preventDataLoading
     */
    public function testMove()
    {
        $this->setExpectedException('RuntimeException');

        $db = JFactory::getDbo();

        $table = new F0FTableNested('#__foftest_nestedsets', 'id', $db);
        $table->move(1);
    }

    /**
     * @group               nestedTestCreate
     * @group               F0FTableNested
     * @covers              F0FTableNested::create
     * @dataProvider        NestedDataprovider::getTestCreate
     */
    public function testCreate($test)
    {
        $db = JFactory::getDbo();

        $matcher = $this->never();

        if(!$test['root'])
        {
            $matcher = $this->once();
        }

        $table = $this->getMock('F0FTableNested', array('insertAsChildOf', 'getParent'), array('#__foftest_nestedsets', 'foftest_nestedset_id', &$db));
        $table->expects($this->once())->method('insertAsChildOf')->will($this->returnValue(null));
        // This is just a little trick, so insertAsChildOf won't complain about the argument passed
        $table->expects($matcher)->method('getParent')->willReturnSelf();

        $table->load($test['loadid']);
        $table->create($test['data']);
    }

    /**
     * @group               nestedTestCreate
     * @group               F0FTableNested
     * @covers              F0FTableNested::create
     * @preventDataLoading
     */
    public function testCreateNotLoaded()
    {
        $this->setExpectedException('RuntimeException');

        $table = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $table->create(array());
    }

    /**
     * @group               nestedTestInsertAsRoot
     * @group               F0FTableNested
     * @covers              F0FTableNested::insertAsRoot
     */
    public function testInsertAsRoot()
    {
        $table = F0FTable::getAnInstance('Nestedset', 'FoftestTable');

        $table->title = 'New root';
        $table->insertAsRoot();

        $this->assertTrue($table->isRoot(), 'F0FTableNested::insertAsRoot failed to create a new root');
    }

    /**
     * @group               nestedTestInsertAsRoot
     * @group               F0FTableNested
     * @covers              F0FTableNested::insertAsRoot
     */
    public function testInsertAsRootException()
    {
        $this->setExpectedException('RuntimeException');

        $table = F0FTable::getAnInstance('Nestedset', 'FoftestTable');

        $table->load(1);
        $table->insertAsRoot();
    }

    /**
     * @group               nestedTestInsertAsFirstChildOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::insertAsFirstChildOf
     * @dataProvider        NestedDataprovider::getTestInsertAsFirstChildOf
     */
    public function testInsertAsFirstChildOf($test)
    {
        /** @var F0FTableNested $table */
        /** @var F0FTableNested $parent */

        $db = JFactory::getDbo();

        $table  = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $parent = $table->getClone();

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        if($test['title'])
        {
            $table->title = $test['title'];
        }

        $parent->load($test['parentid']);
        $parentLft = $parent->lft;
        $parentRgt = $parent->rgt;

        $return = $table->insertAsFirstChildOf($parent);

        $this->assertInstanceOf('F0FTableNested', $return, 'F0FTableNested::insertAsFirstChildOf should return an instance of itself for chaining');

        // Assertions on the objects
        $this->assertNotEquals($test['loadid'], $table->getId(), 'F0FTableNested::insertAsFirstChildOf should always create a new node');

        $this->assertEquals($parentLft, $parent->lft, 'F0FTableNested::insertAsFirstChildOf should not touch the lft value of the parent');
        $this->assertEquals($parentRgt + 2, $parent->rgt, 'F0FTableNested::insertAsFirstChildOf should increase the rgt value by 2');
        $this->assertEquals(1, $table->rgt - $table->lft, 'F0FTableNested::insertAsFirstChildOf should insert the node as leaf');
        $this->assertEquals(1, $table->lft - $parent->lft, 'F0FTableNested::insertAsFirstChildOf should insert the node as first child');

        // Great, the returned objects are ok, what about the ACTUAL data saved inside the db?
        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$table->foftest_nestedset_id);
        $nodeDb = $db->setQuery($query)->loadObject();

        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$parent->foftest_nestedset_id);
        $parentDb = $db->setQuery($query)->loadObject();

        $this->assertEquals($table->lft, $nodeDb->lft, 'F0FTableNested::insertAsFirstChildOf Children object and database lft values are not the same');
        $this->assertEquals($table->rgt, $nodeDb->rgt, 'F0FTableNested::insertAsFirstChildOf Children object and database rgt values are not the same');
        $this->assertEquals($parent->lft, $parentDb->lft, 'F0FTableNested::insertAsFirstChildOf Parent object and database lft values are not the same');
        $this->assertEquals($parent->rgt, $parentDb->rgt, 'F0FTableNested::insertAsFirstChildOf Parnet object and database rgt values are not the same');
    }

    /**
     * @group               nestedTestInsertAsFirstChildOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::insertAsFirstChildOf
     */
    public function testInsertAsFirstChildOfException()
    {
        $this->setExpectedException('RuntimeException');

        $table  = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $parent = $table->getClone();

        $table->insertAsFirstChildOf($parent);
    }

    /**
     * @group               nestedTestInsertAsLastChildOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::insertAsLastChildOf
     * @dataProvider        NestedDataprovider::getTestInsertAsLastChildOf
     */
    public function testInsertAsLastChildOf($test)
    {
        /** @var F0FTableNested $table */
        /** @var F0FTableNested $parent */

        $db = JFactory::getDbo();

        $table  = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $parent = $table->getClone();

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        if($test['title'])
        {
            $table->title = $test['title'];
        }

        $parent->load($test['parentid']);
        $parentLft = $parent->lft;
        $parentRgt = $parent->rgt;

        $return = $table->insertAsLastChildOf($parent);

        $this->assertInstanceOf('F0FTableNested', $return, 'F0FTableNested::insertAsLastChildOf should return an instance of itself for chaining');

        // Assertions on the objects
        $this->assertNotEquals($test['loadid'], $table->getId(), 'F0FTableNested::insertAsLastChildOf should always create a new node');

        $this->assertEquals($parentLft, $parent->lft, 'F0FTableNested::insertAsLastChildOf should not touch the lft value of the parent');
        $this->assertEquals($parentRgt + 2, $parent->rgt, 'F0FTableNested::insertAsLastChildOf should increase the rgt value by 2');
        $this->assertEquals(1, $table->rgt - $table->lft, 'F0FTableNested::insertAsLastChildOf should insert the node as leaf');
        $this->assertEquals(1, $parent->rgt - $table->rgt, 'F0FTableNested::insertAsLastChildOf should insert the node as last child');

        // Great, the returned objects are ok, what about the ACTUAL data saved inside the db?
        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$table->foftest_nestedset_id);
        $nodeDb = $db->setQuery($query)->loadObject();

        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$parent->foftest_nestedset_id);
        $parentDb = $db->setQuery($query)->loadObject();

        $this->assertEquals($table->lft, $nodeDb->lft, 'F0FTableNested::insertAsLastChildOf Children object and database lft values are not the same');
        $this->assertEquals($table->rgt, $nodeDb->rgt, 'F0FTableNested::insertAsLastChildOf Children object and database rgt values are not the same');
        $this->assertEquals($parent->lft, $parentDb->lft, 'F0FTableNested::insertAsLastChildOf Parent object and database lft values are not the same');
        $this->assertEquals($parent->rgt, $parentDb->rgt, 'F0FTableNested::insertAsLastChildOf Parent object and database rgt values are not the same');
    }

    /**
     * @group               nestedTestInsertAsLastChildOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::insertAsLastChildOf
     */
    public function testInsertAsLastChildOfException()
    {
        $this->setExpectedException('RuntimeException');

        $table  = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $parent = $table->getClone();

        $table->insertAsLastChildOf($parent);
    }

    /**
     * @group               nestedTestInsertLeftOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::insertLeftOf
     * @dataProvider        NestedDataprovider::getTestInsertLeftOf
     */
    public function testInsertLeftOf($test)
    {
        /** @var F0FTableNested $table */
        /** @var F0FTableNested $sibling */

        $db = JFactory::getDbo();

        $table   = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $sibling = $table->getClone();

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        if($test['title'])
        {
            $table->title = $test['title'];
        }

        $sibling->load($test['siblingid']);
        $siblingLft = $sibling->lft;
        $siblingRgt = $sibling->rgt;

        $return = $table->insertLeftOf($sibling);

        $this->assertInstanceOf('F0FTableNested', $return, 'F0FTableNested::insertLeftOf should return an instance of itself for chaining');

        // Assertions on the objects
        $this->assertNotEquals($test['loadid'], $table->getId(), 'F0FTableNested::insertLeftOf should always create a new node');
        $this->assertEquals($siblingLft + 2, $sibling->lft, 'F0FTableNested::insertLeftOf should increase the lft value by 2');
        $this->assertEquals($siblingRgt + 2, $sibling->rgt, 'F0FTableNested::insertLeftOf should increase the rgt value by 2');
        $this->assertEquals(1, $table->rgt - $table->lft, 'F0FTableNested::insertLeftOf should insert the node as leaf');
        $this->assertEquals(1, $sibling->lft - $table->rgt, 'F0FTableNested::insertLeftOf should insert the node on the left of the sibling');

        // Great, the returned objects are ok, what about the ACTUAL data saved inside the db?
        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$table->foftest_nestedset_id);
        $nodeDb = $db->setQuery($query)->loadObject();

        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$sibling->foftest_nestedset_id);
        $siblingDb = $db->setQuery($query)->loadObject();

        $this->assertEquals($table->lft, $nodeDb->lft, 'F0FTableNested::insertLeftOf Node object and database lft values are not the same');
        $this->assertEquals($table->rgt, $nodeDb->rgt, 'F0FTableNested::insertLeftOf Node object and database rgt values are not the same');
        $this->assertEquals($sibling->lft, $siblingDb->lft, 'F0FTableNested::insertLeftOf Sibling object and database lft values are not the same');
        $this->assertEquals($sibling->rgt, $siblingDb->rgt, 'F0FTableNested::insertLeftOf Sibling object and database rgt values are not the same');
    }

    /**
     * @group               nestedTestInsertLeftOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::insertLeftOf
     */
    public function testInsertLeftOfException()
    {
        $this->setExpectedException('RuntimeException');

        $table   = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $sibling = $table->getClone();

        $table->insertLeftOf($sibling);
    }

    /**
     * @group               nestedTestInsertRightOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::insertRightOf
     * @dataProvider        NestedDataprovider::getTestInsertRightOf
     */
    public function testInsertRightOf($test)
    {
        /** @var F0FTableNested $table */
        /** @var F0FTableNested $sibling */

        $db = JFactory::getDbo();

        $table   = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $sibling = $table->getClone();

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        if($test['title'])
        {
            $table->title = $test['title'];
        }

        $sibling->load($test['siblingid']);
        $siblingLft = $sibling->lft;
        $siblingRgt = $sibling->rgt;

        $return = $table->insertRightOf($sibling);

        $this->assertInstanceOf('F0FTableNested', $return, 'F0FTableNested::insertRightOf should return an instance of itself for chaining');

        // Assertions on the objects
        $this->assertNotEquals($test['loadid'], $table->getId(), 'F0FTableNested::insertRightOf should always create a new node');
        $this->assertEquals($siblingLft, $sibling->lft, 'F0FTableNested::insertRightOf should not modify the lft value');
        $this->assertEquals($siblingRgt, $sibling->rgt, 'F0FTableNested::insertRightOf should not modify the rgt value');
        $this->assertEquals(1, $table->rgt - $table->lft, 'F0FTableNested::insertRightOf should insert the node as leaf');
        $this->assertEquals(1, $table->lft - $sibling->rgt, 'F0FTableNested::insertRightOf should insert the node on the right of the sibling');

        // Great, the returned objects are ok, what about the ACTUAL data saved inside the db?
        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$table->foftest_nestedset_id);
        $nodeDb = $db->setQuery($query)->loadObject();

        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$sibling->foftest_nestedset_id);
        $siblingDb = $db->setQuery($query)->loadObject();

        $this->assertEquals($table->lft, $nodeDb->lft, 'F0FTableNested::insertRightOf Node object and database lft values are not the same');
        $this->assertEquals($table->rgt, $nodeDb->rgt, 'F0FTableNested::insertRightOf Node object and database rgt values are not the same');
        $this->assertEquals($sibling->lft, $siblingDb->lft, 'F0FTableNested::insertRightOf Sibling object and database lft values are not the same');
        $this->assertEquals($sibling->rgt, $siblingDb->rgt, 'F0FTableNested::insertRightOf Sibling object and database rgt values are not the same');
    }

    /**
     * @group               nestedTestInsertRightOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::insertRightOf
     */
    public function testInsertRightOfException()
    {
        $this->setExpectedException('RuntimeException');

        $table   = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $sibling = $table->getClone();

        $table->insertRightOf($sibling);
    }

    /**
     * @group               nestedTestMoveLeft
     * @group               F0FTableNested
     * @covers              F0FTableNested::moveLeft
     * @dataProvider        NestedDataprovider::getTestMoveLeft
     */
    public function testMoveLeft($test, $check)
    {
        $db = JFactory::getDbo();

        $table = m::mock('FoftestTableNestedset[moveToLeftOf]', array('#__foftest_nestedsets', 'foftest_nestedset_id', &$db, array('_table_class' => 'FoftestTableNestedset')));
        $table->shouldReceive('moveToLeftOf')
              ->times((int) $check['move'])
              ->with(
                m::on(function($leftSibling) use($check) {
                    return $leftSibling->foftest_nestedset_id == $check['leftSibling'];
              }))
              ->andReturn(true);

        $table->load($test['loadid']);

        $table->moveLeft();
    }

    /**
     * @group               nestedTestMoveLeft
     * @group               F0FTableNested
     * @covers              F0FTableNested::moveLeft
     */
    public function testMoveLeftException()
    {
        $this->setExpectedException('RuntimeException');

        $table   = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $table->moveLeft();
    }

    /**
     * @group               nestedTestMoveRight
     * @group               F0FTableNested
     * @covers              F0FTableNested::moveRight
     * @dataProvider        NestedDataprovider::getTestMoveRight
     */
    public function testMoveRight($test, $check)
    {
        $db = JFactory::getDbo();

        $table = m::mock('FoftestTableNestedset[moveToRightOf]', array('#__foftest_nestedsets', 'foftest_nestedset_id', &$db, array('_table_class' => 'FoftestTableNestedset')));
        $table->shouldReceive('moveToRightOf')
            ->times((int) $check['move'])
            ->with(
                m::on(function($leftSibling) use($check) {
                    return $leftSibling->foftest_nestedset_id == $check['rightSibling'];
                }))
            ->andReturn(true);

        $table->load($test['loadid']);

        $table->moveRight();
    }

    /**
     * @group               nestedTestMoveRight
     * @group               F0FTableNested
     * @covers              F0FTableNested::moveRight
     */
    public function testMoveRightException()
    {
        $this->setExpectedException('RuntimeException');

        $table   = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $table->moveRight();
    }

    /**
     * @group               nestedTestMoveToLeftOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::moveToLeftOf
     * @dataProvider        NestedDataprovider::getTestMoveToLeftOf
     */
    public function testMoveToLeftOf($test, $check)
    {
        /** @var F0FTableNested $table */
        /** @var F0FTableNested $sibling */

        $db = JFactory::getDbo();

        $table   = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $sibling = $table->getClone();

        // Am I request to create a different root?
        if($test['newRoot'])
        {
            $root = $table->getClone();
            $root->title = 'New root';
            $root->insertAsRoot();

            $child = $table->getClone();
            $child->title = 'First child 2nd root';
            $child->insertAsChildOf($root);

            $child->reset();

            $child->title = 'Second child 2nd root';
            $child->insertAsChildOf($root);
        }

        $table->load($test['loadid']);
        $sibling->load($test['siblingid']);

        $return = $table->moveToLeftOf($sibling);

        $this->assertInstanceOf('F0FTableNested', $return, 'F0FTableNested::moveToLeftOf should return an instance of itself for chaining');

        // Assertions on the objects
        $this->assertEquals($check['table']['lft'], $table->lft, 'F0FTableNested::moveToLeftOf failed to assign the correct lft value to the node');
        $this->assertEquals($check['table']['rgt'], $table->rgt, 'F0FTableNested::moveToLeftOf failed to assign the correct rgt value to the node');

        // Great, the returned objects are ok, what about the ACTUAL data saved inside the db?
        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$table->foftest_nestedset_id);
        $nodeDb = $db->setQuery($query)->loadObject();

        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$sibling->foftest_nestedset_id);
        $siblingDb = $db->setQuery($query)->loadObject();

        $this->assertEquals($table->lft, $nodeDb->lft, 'F0FTableNested::moveToLeftOf Node object and database lft values are not the same');
        $this->assertEquals($table->rgt, $nodeDb->rgt, 'F0FTableNested::moveToLeftOf Node object and database rgt values are not the same');
        $this->assertEquals($check['sibling']['lft'], $siblingDb->lft, 'F0FTableNested::moveToLeftOf Saved the wrong lft value for the sibling');
        $this->assertEquals($check['sibling']['rgt'], $siblingDb->rgt, 'F0FTableNested::moveToLeftOf Saved the wrong rgt value for the sibling');
    }

    /**
     * @group               nestedTestMoveToLeftOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::moveToLeftOf
     * @dataProvider        NestedDataprovider::getTestMoveToLeftOfException
     */
    public function testMoveToLeftOfException($test)
    {
        $this->setExpectedException('RuntimeException');

        $table   = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $sibling = $table->getClone();

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        if($test['siblingid'])
        {
            $sibling->load($test['siblingid']);
        }

        $table->moveToLeftOf($sibling);
    }

    /**
     * @group               nestedTestMoveToRightOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::moveToRightOf
     * @dataProvider        NestedDataprovider::getTestMoveToRightOf
     */
    public function testMoveToRightOf($test, $check)
    {
        /** @var F0FTableNested $table */
        /** @var F0FTableNested $sibling */

        $db = JFactory::getDbo();

        $table   = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $sibling = $table->getClone();

        // Am I request to create a different root?
        if($test['newRoot'])
        {
            $root = $table->getClone();
            $root->title = 'New root';
            $root->insertAsRoot();

            $child = $table->getClone();
            $child->title = 'First child 2nd root';
            $child->insertAsChildOf($root);

            $child->reset();

            $child->title = 'Second child 2nd root';
            $child->insertAsChildOf($root);
        }

        $table->load($test['loadid']);
        $sibling->load($test['siblingid']);

        $return = $table->moveToRightOf($sibling);

        $this->assertInstanceOf('F0FTableNested', $return, 'F0FTableNested::moveToRightOf should return an instance of itself for chaining');

        // Assertions on the objects
        $this->assertEquals($check['table']['lft'], $table->lft, 'F0FTableNested::moveToRightOf failed to assign the correct lft value to the node');
        $this->assertEquals($check['table']['rgt'], $table->rgt, 'F0FTableNested::moveToRightOf failed to assign the correct rgt value to the node');

        // Great, the returned objects are ok, what about the ACTUAL data saved inside the db?
        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__foftest_nestedsets')
            ->where('foftest_nestedset_id = '.$table->foftest_nestedset_id);
        $nodeDb = $db->setQuery($query)->loadObject();

        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__foftest_nestedsets')
            ->where('foftest_nestedset_id = '.$sibling->foftest_nestedset_id);
        $siblingDb = $db->setQuery($query)->loadObject();

        $this->assertEquals($table->lft, $nodeDb->lft, 'F0FTableNested::moveToRightOf Node object and database lft values are not the same');
        $this->assertEquals($table->rgt, $nodeDb->rgt, 'F0FTableNested::moveToRightOf Node object and database rgt values are not the same');
        $this->assertEquals($check['sibling']['lft'], $siblingDb->lft, 'F0FTableNested::moveToRightOf Saved the wrong lft value for the sibling');
        $this->assertEquals($check['sibling']['rgt'], $siblingDb->rgt, 'F0FTableNested::moveToRightOf Saved the wrong rgt value for the sibling');
    }

    /**
     * @group               nestedTestMoveToRightOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::moveToRightOf
     * @dataProvider        NestedDataprovider::getTestMoveToRightOfException
     */
    public function testMoveToRightOfException($test)
    {
        $this->setExpectedException('RuntimeException');

        $table   = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $sibling = $table->getClone();

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        if($test['siblingid'])
        {
            $sibling->load($test['siblingid']);
        }

        $table->moveToRightOf($sibling);
    }

    /**
     * @group               nestedTestMakeFirstChildOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::makeFirstChildOf
     * @dataProvider        NestedDataprovider::getTestMakeFirstChildOf
     */
    public function testMakeFirstChildOf($test, $check)
    {
        /** @var F0FTableNested $table */
        /** @var F0FTableNested $parent */

        $db = JFactory::getDbo();

        $table  = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $parent = $table->getClone();

        $table->load($test['loadid']);
        $parent->load($test['parentid']);

        $return = $table->makeFirstChildOf($parent);

        $this->assertInstanceOf('F0FTableNested', $return, 'F0FTableNested::makeFirstChildOf should return an instance of itself for chaining');

        // Assertions on the objects
        $this->assertEquals($check['table']['lft'], $table->lft, 'F0FTableNested::makeFirstChildOf failed to assign the correct lft value to the node');
        $this->assertEquals($check['table']['rgt'], $table->rgt, 'F0FTableNested::makeFirstChildOf failed to assign the correct rgt value to the node');

        // Great, the returned objects are ok, what about the ACTUAL data saved inside the db?
        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$table->foftest_nestedset_id);
        $nodeDb = $db->setQuery($query)->loadObject();

        $query = $db->getQuery(true)
                    ->select('*')
                    ->from('#__foftest_nestedsets')
                    ->where('foftest_nestedset_id = '.$parent->foftest_nestedset_id);
        $parentDb = $db->setQuery($query)->loadObject();

        $this->assertEquals($table->lft, $nodeDb->lft, 'F0FTableNested::makeFirstChildOf Node object and database lft values are not the same');
        $this->assertEquals($table->rgt, $nodeDb->rgt, 'F0FTableNested::makeFirstChildOf Node object and database rgt values are not the same');
        $this->assertEquals($check['parent']['lft'], $parentDb->lft, 'F0FTableNested::makeFirstChildOf Saved the wrong lft value for the parent');
        $this->assertEquals($check['parent']['rgt'], $parentDb->rgt, 'F0FTableNested::makeFirstChildOf Saved the wrong rgt value for the parent');
    }

    /**
     * @group               nestedTestMakeFirstChildOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::makeFirstChildOf
     * @dataProvider        NestedDataprovider::getTestMakeFirstChildOfException
     */
    public function testMakeFirstChildOfException($test)
    {
        $this->setExpectedException('RuntimeException');

        $table  = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $parent = $table->getClone();

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        if($test['siblingid'])
        {
            $parent->load($test['parentid']);
        }

        $table->makeFirstChildOf($parent);
    }

    /**
     * @group               nestedTestMakeLastChildOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::makeLastChildOf
     * @dataProvider        NestedDataprovider::getTestMakeLastChildOf
     */
    public function testMakeLastChildOf($test, $check)
    {
        /** @var F0FTableNested $table */
        /** @var F0FTableNested $parent */

        $db = JFactory::getDbo();

        $table  = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $parent = $table->getClone();

        $table->load($test['loadid']);
        $parent->load($test['parentid']);

        $return = $table->makeLastChildOf($parent);

        $this->assertInstanceOf('F0FTableNested', $return, 'F0FTableNested::makeLastChildOf should return an instance of itself for chaining');

        // Assertions on the objects
        $this->assertEquals($check['table']['lft'], $table->lft, 'F0FTableNested::makeLastChildOf failed to assign the correct lft value to the node');
        $this->assertEquals($check['table']['rgt'], $table->rgt, 'F0FTableNested::makeLastChildOf failed to assign the correct rgt value to the node');

        // Great, the returned objects are ok, what about the ACTUAL data saved inside the db?
        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__foftest_nestedsets')
            ->where('foftest_nestedset_id = '.$table->foftest_nestedset_id);
        $nodeDb = $db->setQuery($query)->loadObject();

        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__foftest_nestedsets')
            ->where('foftest_nestedset_id = '.$parent->foftest_nestedset_id);
        $parentDb = $db->setQuery($query)->loadObject();

        $this->assertEquals($table->lft, $nodeDb->lft, 'F0FTableNested::makeLastChildOf Node object and database lft values are not the same');
        $this->assertEquals($table->rgt, $nodeDb->rgt, 'F0FTableNested::makeLastChildOf Node object and database rgt values are not the same');
        $this->assertEquals($check['parent']['lft'], $parentDb->lft, 'F0FTableNested::makeLastChildOf Saved the wrong lft value for the parent');
        $this->assertEquals($check['parent']['rgt'], $parentDb->rgt, 'F0FTableNested::makeLastChildOf Saved the wrong rgt value for the parent');
    }

    /**
     * @group               nestedTestMakeLastChildOf
     * @group               F0FTableNested
     * @covers              F0FTableNested::makeLastChildOf
     * @dataProvider        NestedDataprovider::getTestMakeLastChildOfException
     */
    public function testMakeLastChildOfException($test)
    {
        $this->setExpectedException('RuntimeException');

        $table  = F0FTable::getAnInstance('Nestedset', 'FoftestTable');
        $parent = $table->getClone();

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        if($test['siblingid'])
        {
            $parent->load($test['parentid']);
        }

        $table->makeLastChildOf($parent);
    }

    /**
     * @group               nestedTestMakeRoot
     * @group               F0FTableNested
     * @covers              F0FTableNested::makeRoot
     * @dataProvider        NestedDataprovider::getTestMakeRoot
     */
    public function testMakeRoot($test)
    {
        // TODO Rewrite this test, since it's testing logic out of the scope of the function under test
        $db = JFactory::getDbo();

        if($test['setup'])
        {
            $db->setQuery('TRUNCATE #__foftest_nestedsets')->execute();

            foreach($test['setup'] as $row)
            {
                $dummy = (object) $row;
                $db->insertObject('#__foftest_nestedsets', $dummy);
            }
        }

        $table = F0FTable::getAnInstance('Nestedset', 'FoftestTable');

        $table->load($test['loadid']);

        $result = $table->makeRoot();

        // Let's wipe the cache, so I can run all the logic again
        TestReflection::invoke($table, 'resetTreeCache');

        $this->assertInstanceOf('F0FTableNested', $result, 'F0FTableNested::makeRoot should return an instance of itself for chaining');
        $this->assertTrue($table->isRoot(), 'F0FTableNested::makeRoot the new node is not a root one');
    }
}

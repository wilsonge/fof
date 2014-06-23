<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'nestedDataprovider.php';

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
}

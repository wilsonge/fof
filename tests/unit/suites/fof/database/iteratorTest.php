<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Iterator
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

class FOFDatabaseIteratorTest extends FtestCaseDatabase
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
     * @group   FOFDatabaseIterator
     * @group   iteratorConstruct
     * @covers  FOFDatabaseIterator::__construct
     */
    public function test__construct()
    {
        $db = JFactory::getDbo();

        $iterator = FOFDatabaseIterator::getIterator('Mysql', $db, null, 'FoftestTableFoobar');
        $this->assertInstanceOf('FOFDatabaseIteratorMysql', $iterator, 'FOFDatabaseIterator loaded the wrong class');
    }

    /**
     * @group   FOFDatabaseIterator
     * @group   iteratorConstruct
     * @covers  FOFDatabaseIterator::__construct
     * @expectedException InvalidArgumentException
     */
    public function test__constructException()
    {
        $db = JFactory::getDbo();
        $iterator = FOFDatabaseIterator::getIterator('Mysql', $db, null, 'WrongTable');
    }
}

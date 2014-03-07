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
     * @group               relationsConstruct
     * @group               FOFTableRelations
     * @covers              FOFTableRelations::__construct
     */
    /*public function test__construct()
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'parts'));
        $table 		     = FOFTable::getAnInstance('Part', 'FoftestTable', $config);
        $table->load(1);

        $relation = $table->getRelations();
        $relation->addMultipleRelation('partgroups', 'FoftestTableGroup', 'foftest_part_id', 'foftest_part_id', 'foftest_group_id', 'foftest_group_id', '#__foftest_parts_groups');
        $items = $relation->getMultiple();
    }*/
}
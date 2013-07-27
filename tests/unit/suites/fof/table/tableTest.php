<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Inflector
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'tableDataprovider.php';

class FOFTableTest extends FtestCaseDatabase
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
	 * @preventDataLoading
	 */
	public function testSetKnownFields()
	{
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));

		$table 		= FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);

		$knownFields = array(
			'foo',
			'bar',
			'baz',
		);
		$table->setKnownFields($knownFields);

		$this->assertAttributeEquals($knownFields, 'knownFields', $table, 'Known fields set differ from defined list');
	}

	/**
	 * @preventDataLoading
	 */
	public function testGetKnownFields()
	{
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));

		$table 		= FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);

		$knownFields = array(
			'foo',
			'bar',
			'baz',
		);
		$table->setKnownFields($knownFields);

		$result = $table->getKnownFields();

		$this->assertEquals($knownFields, $result, 'Known fields fetched differ from defined list');
	}

	/**
	 * @preventDataLoading
	 */
	public function testAddKnownField()
	{
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));

		$table 		= FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);

		$table->addKnownField('foo');
		$table->addKnownField('bar');

		$known_fields = $this->readAttribute($table, 'knownFields');

		$this->assertContains('foo', $known_fields, 'Known fields set differ from defined list');
		$this->assertContains('bar', $known_fields, 'Known fields set differ from defined list');
	}

	/**
	 * @preventDataLoading
	 */
	public function testRemoveKnownField()
	{
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));

		$table 		= FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);

		$table->addKnownField('foo');
		$table->removeKnownField('foo');

		$known_fields = $this->readAttribute($table, 'knownFields');

		$this->assertNotContains('foo', $known_fields, 'Known fields set differ from defined list');
	}

	/**
	 * @groupXX       tableLoad
	 */
	public function testLoad()
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));
        $table 		     = FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);

        $reflection = new ReflectionClass($table);
        $property   = $reflection->getProperty('_tableExists');
        $property->setAccessible(true);
        $property->setValue($table, false);

        $this->assertNull($table->load(), 'Load() should return NULL when no table exists');

        $property->setValue($table, true);

        $this->assertNull($table->load(), 'Load() should return NULL when the primary key has no value');

        $rc = $table->load(1);
        $this->assertTrue($rc, 'Successfully load should return TRUE');
        $this->assertEquals('Guinea Pig row', $table->title, 'Load() by primary key failed');

        $table->load(1);
        $table->load('FOOBAR', false);
        $this->assertEquals('Guinea Pig row', $table->title, "Load() by non-existent primary key (without reset) shouldn't touch table fields");

        // Reset everything
        $table->reset();
        $table->foftest_foobar_id = null;

        $table->load(array('slug' => 'guinea-pig-row'));
        $this->assertEquals(1, $table->foftest_foobar_id, 'Load() by fields to match failed');
    }

	/**
	 * @group           tableLoad
	 * @dataProvider    getTestLoadJoined
	 */
	public function testLoadJoined($tableinfo, $test, $check)
	{
		require_once JPATH_TESTS.'/unit/core/table/table.php';

		$db = JFactory::getDbo();

		$table = new FtestTable($tableinfo['table'], $tableinfo['id'], $db, $tableinfo['config']);

		foreach($check['columns'] as $column)
		{
			$this->assertObjectHasAttribute($column, $table, sprintf('Joined field %s not set', $column));
		}

		$rc = $table->load($test['cid']);
		$this->assertEquals($check['return'], $rc, 'Load with joined fields: wrong return value');
	}

	/**
	 * @preventDataLoading
	 * @group               tableCheck
	 */
	public function testCheck()
    {
        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));
        $table 		     = FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);

        $reflection = new ReflectionClass($table);
        $property   = $reflection->getProperty('_autoChecks');
        $property->setAccessible(true);
        $property->setValue($table, false);

        $this->assertTrue($table->check(), 'Check() should return true when autoChecks are disabled');

        $property->setValue($table, true);

        $table->foftest_foobar_id   = 999;
        $table->title               = 'Dummy title';
        $table->slug                = 'dummy-title';
        $table->enabled             = 1;
        $table->ordering            = 99;
        $table->created_by          = 0;
        $table->created_on          = '0000-00-00 00:00:00';
        $table->modified_by         = 0;
        $table->modified_on         = '0000-00-00 00:00:00';
        $table->locked_by           = 0;
        $table->locked_on           = '0000-00-00 00:00:00';

        $this->assertTrue($table->check(), 'Check() should return true when some "magic" field is empty');

        $table->foftest_foobar_id   = 999;
        $table->title               = '';
        $table->slug                = '';
        $table->enabled             = 1;
        $table->ordering            = 99;
        $table->created_by          = 0;
        $table->created_on          = '0000-00-00 00:00:00';
        $table->modified_by         = 0;
        $table->modified_on         = '0000-00-00 00:00:00';
        $table->locked_by           = 0;
        $table->locked_on           = '0000-00-00 00:00:00';

        $this->assertFalse($table->check(), 'Check() should return false when some required field is empty');

        $table->foftest_foobar_id   = 999;
        $table->title               = '';
        $table->slug                = '';
        $table->enabled             = 1;
        $table->ordering            = 99;
        $table->created_by          = 0;
        $table->created_on          = '0000-00-00 00:00:00';
        $table->modified_by         = 0;
        $table->modified_on         = '0000-00-00 00:00:00';
        $table->locked_by           = 0;
        $table->locked_on           = '0000-00-00 00:00:00';

        $table->setSkipChecks(array('title', 'slug'));

        $this->assertTrue($table->check(), 'Check() should return false when some required field is empty');
    }

	public function testReset()
	{
		$db = JFactory::getDbo();
		$methods = array('onBeforeReset', 'onAfterReset');
		$constr_args = array('jos_foftest_foobars', 'foftest_foobar_id', &$db);

		$table = $this->getMock('FOFTable',	$methods, $constr_args,	'',	true, true, true, true);

		$table->expects($this->any())->method('onBeforeReset')->will($this->returnValue(false));
		$table->expects($this->any())->method('onAfterReset') ->will($this->returnValue(true));

		$this->assertFalse($table->reset(), 'Reset should return FALSE when onBeforeReset returns FALSE');

		unset($table);

		// Rebuild the mock to return true on onBeforeReset
		$table = $this->getMock('FOFTable', $methods, $constr_args,	'',	true, true,	true, true);

		$table->expects($this->any())->method('onBeforeReset')->will($this->returnValue(true));
		$table->expects($this->any())->method('onAfterReset') ->will($this->returnValue(true));

		$table->load(1);
		$rc = $table->reset();

		// First of all let's check the return value
		$this->assertNull($rc, 'Reset should return NULL when onBeforeReset returns TRUE');

		// Then let's check if reset method worked
		// @TODO we must check for additional fields, like joined columns
		// This test is not 100% correct, we must change it after FOFTable refactoring
		$fields  = $table->getTableFields();
		$success = true;
		foreach($fields as $field => $class)
		{
			// Primary key shouldn't be resetted
			if($field == $table->getKeyName() && !$table->$field)
			{
				$success = false;
				break;
			}
			elseif($field != $table->getKeyName() && $table->$field)
			{
				$success = false;
				break;
			}
		}

		$this->assertTrue($success, 'Reset method failed on resetting table properties');

		unset($table);

		// Rebuild the mock to return true on onBeforeReset AND false on onAfterReset
		$table = $this->getMock('FOFTable', $methods, $constr_args,	'', true, true,	true, true);

		$table->expects($this->any())->method('onBeforeReset')->will($this->returnValue(true));
		$table->expects($this->any())->method('onAfterReset') ->will($this->returnValue(false));

		$table->load(1);

		$this->assertFalse($table->reset(), 'Reset should return FALSE when onAfterReset is FALSE');
	}

    /**
     * @preventDataLoading
     * @dataProvider    getTestBind
     */
    public function testBind($onBefore, $returnValue, $toBind, $toSkip, $toCheck)
    {
        $db          = JFactory::getDbo();
        $methods     = array('onBeforeBind');
        $constr_args = array('jos_foftest_foobars', 'foftest_foobar_id', &$db);

        $table = $this->getMock('FOFTable',	$methods, $constr_args,	'',	true, true, true, true);
        $table->expects($this->any())->method('onBeforeBind')->will($this->returnValue($onBefore));

        $rc = $table->bind($toBind, $toSkip);
        $this->assertEquals($returnValue, $rc, 'Bind() Wrong return value');

        // Only if I'm executing the bind function I do more checks
        if($returnValue)
        {
            foreach($toCheck as $check)
            {
                $this->assertEquals($check['value'], $table->$check['field'], $check['msg']);
            }
        }
    }

	/**
	 * @preventDataLoading
	 */
	public function testBindException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));
        $table 		     = FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);
        $table->bind('This is a wrong argument');
    }

    /**
     * @group           tableStore
     * @dataProvider    getTestStore
     */
    public function testStore($events, $tableinfo, $test, $check)
    {
        $db          = JFactory::getDbo();
        $methods     = array('onBeforeStore', 'onAfterStore');
        $constr_args = array($tableinfo['table'], $tableinfo['id'], &$db);

        $table = $this->getMock('FOFTable',	$methods, $constr_args,	'',	true, true, true, true);

        // Mocking these methods will prevent some FOF features (ie slug creation, created_by set up and so on)
        // I think it's ok since we're going to test that features when we'll test these methods,
        // now we only care about the store() method
        $table->expects($this->any())->method('onBeforeStore')->will($this->returnValue($events['before']));
        $table->expects($this->any())->method('onAfterStore')->will($this->returnValue($events['after']));

        if($test['alias'])
        {
            $table->setColumnAlias('asset_id', $test['alias']);
        }

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        // We have to manually provide this info, since we can't use the getInstance method (we have to mock)
        if($test['assetkey'])
        {
            $table->setAssetKey($test['assetkey']);
        }

        $table->bind($test['bind']);

        if($test['nullable'])
        {
            foreach($test['nullable'] as $field => $value)
            {
                $table->$field = null;
            }
        }

        $rc = $table->store($test['updateNulls']);
        $this->assertEquals($check['return'], $rc, 'Store: wrong return value');

        if($check['more'])
        {
            $tocheck = $test['bind'];
            if($test['updateNulls'])
            {
                $tocheck = array_merge($tocheck, $test['nullable']);
            }

            $k = $table->getKeyName();
            $query = $db->getQuery(true)
                        ->select('*')
                        ->from($tableinfo['table'])
                        ->where($tableinfo['id'].' = '.$table->$k);
            $row = $db->setQuery($query)->loadAssoc();

            foreach($tocheck as $field => $value)
            {
                if($test['updateNulls'] && array_key_exists($field, $test['nullable']))
                {
                    $this->assertEmpty($row[$field], sprintf('Store: wrong stored value, %s instead of empty', $row[$field]));
                }
                else
                {
                    $this->assertEquals($row[$field], $value, sprintf('Store: wrong stored value, %s instead of %s', $value, $row[$field]));
                }
            }
        }

    }

    /**
     * @group           tableMove
     * @dataProvider    getTestMove
     */
    public function testMove($events, $tableinfo, $test, $check)
    {
        $db          = JFactory::getDbo();
        $methods     = array('onBeforeMove', 'onAfterMove');
        $constr_args = array($tableinfo['table'], $tableinfo['id'], &$db);

        $table = $this->getMock('FOFTable',	$methods, $constr_args,	'',	true, true, true, true);
        $table->expects($this->any())->method('onBeforeMove')->will($this->returnValue($events['before']));
        $table->expects($this->any())->method('onAfterMove')->will($this->returnValue($events['after']));

        if(isset($test['alias']))
        {
            $table->setColumnAlias('ordering', $test['alias']);
        }

        $ordering = $table->getColumnAlias('ordering');

        if($test['id'])
        {
            $table->load($test['id']);
        }

        $rc = $table->move($test['delta'], $test['where']);

        $this->assertEquals($check['return'], $rc, 'Move() wrong return value');

        // Only if I'm executing the move function I do more checks
        if(isset($check['more']) && $check['more'])
        {
            // Is the current record ok?
            $this->assertEquals($check['value'], $table->$ordering, $check['msg']);

            // Let's check that the moved record has the correct ordering
            if(isset($check['find']))
            {
                $table->load($check['find']['id']);
                $this->assertEquals($check['find']['value'], $table->$ordering, $check['find']['msg']);
            }
        }
    }

    /**
     * @preventDataLoading
     * @group           tableMove
     */
    public function testMoveException()
    {
        $this->setExpectedException('UnexpectedValueException');

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'bare'));
        $table 		     = FOFTable::getAnInstance('Bare', 'FoftestTable', $config);
        $table->move(0);
    }

    /**
     * @group           tableReorder
     * @dataProvider    getTestReorder
     */
    public function testReorder($events, $tableinfo, $test, $check)
    {
        $db          = JFactory::getDbo();
        $methods     = array('onBeforeReorder', 'onAfterReorder');
        $constr_args = array($tableinfo['table'], $tableinfo['id'], &$db);

        $table = $this->getMock('FOFTable',	$methods, $constr_args,	'',	true, true, true, true);
        $table->expects($this->any())->method('onBeforeReorder')->will($this->returnValue($events['before']));
        $table->expects($this->any())->method('onAfterReorder')->will($this->returnValue($events['after']));

        if(isset($test['alias']))
        {
            $table->setColumnAlias('ordering', $test['alias']);
        }

        if($test['id'] && $test['ordering'])
        {
            $ordering = $table->getColumnAlias('ordering');

	        $table->setAssetKey('com_foftest.foobar');
            $table->load($test['id']);
            $table->$ordering = $test['ordering'];
            $table->store();
        }

        $rc = $table->reorder($test['where']);

        // First of all, let's check the return value
        $this->assertEquals($check['return'], $rc, 'Reorder() wrong return value');

        if(isset($check['more']) && $check['more'])
        {
            // Then, let's check if the reorder method worked
            $query = $db->getQuery(true)
                        ->select(array($tableinfo['id'], $ordering))
                        ->from($tableinfo['table'])
                        ->order($ordering.' ASC');

            if($test['where'])
            {
                $query->where($test['where']);
            }

            $rows = $db->setQuery($query)->loadAssocList();

            $this->assertEquals($check['list'], $rows, $check['msg']);
        }
    }

    /**
     * @preventDataLoading
     * @group           tableReorder
     */
    public function testReorderException()
    {
        $this->setExpectedException('UnexpectedValueException');

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'bare'));
        $table 		     = FOFTable::getAnInstance('Bare', 'FoftestTable', $config);
        $table->reorder();
    }

    /**
     * @group           tableCheckout
     * @dataProvider    getTestCheckout
     */
    public function testCheckout($tableinfo, $test, $check)
    {
        $db    = JFactory::getDbo();
        $table = new FOFTable($tableinfo['table'], $tableinfo['id'], $db);

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        if($test['alias'])
        {
            $table->setColumnAlias('locked_by', $test['alias']['lockby']);
            $table->setColumnAlias('locked_on', $test['alias']['lockon']);
        }

        $rc = $table->checkout($test['user'], $test['id']);

        $this->assertEquals($check['return'], $rc, 'Checkout() Wrong return value');

        if($check['more'])
        {
            // Let's check if everything is alright
            $pk        = $table->getKeyName();
            $locked_by = $table->getColumnAlias('locked_by');
            $locked_on = $table->getColumnAlias('locked_on');

            $query = $db->getQuery(true)
                        ->select(array($locked_by, $locked_on))
                        ->from($table->getTableName())
                        ->where($pk.' = '.($test['loadid'] ? $test['loadid'] : $test['id']));

            $row = $db->setQuery($query)->loadObject();

            $this->assertEquals($table->$locked_by, $row->$locked_by, 'Checkout() Wrong value for '.$locked_by.' property');
            $this->assertEquals($table->$locked_on, $row->$locked_on, 'Checkout() Wrong value for '.$locked_on.' property');
        }
    }

    /**
     * @group           tableCheckin
     * @dataProvider    getTestCheckin
     */
    public function testCheckin($tableinfo, $test, $check)
    {
        $db    = JFactory::getDbo();
        $table = new FOFTable($tableinfo['table'], $tableinfo['id'], $db);

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        if($test['alias'])
        {
            $table->setColumnAlias('locked_by', $test['alias']['lockby']);
            $table->setColumnAlias('locked_on', $test['alias']['lockon']);
        }

        $rc = $table->checkin($test['id']);

        $this->assertEquals($check['return'], $rc, 'Checkin() Wrong return value');

        if($check['more'])
        {
            // Let's check if everything is alright
            $pk        = $table->getKeyName();
            $locked_by = $table->getColumnAlias('locked_by');
            $locked_on = $table->getColumnAlias('locked_on');

            $query = $db->getQuery(true)
                        ->select(array($locked_by, $locked_on))
                        ->from($table->getTableName())
                        ->where($pk.' = '.($test['loadid'] ? $test['loadid'] : $test['id']));

            $row = $db->setQuery($query)->loadObject();

            $this->assertEquals('0', $table->$locked_by, 'Checkin() Wrong table value for '.$locked_by.' property');
            $this->assertEquals('' , $table->$locked_on, 'Checkin() Wrong table value for '.$locked_on.' property');
            $this->assertEquals('0', $row->$locked_by  , 'Checkin() Wrong db value for '.$locked_by.' property');
            $this->assertEquals('0000-00-00 00:00:00', $row->$locked_on, 'Checkin() Wrong db value for '.$locked_on.' property');
        }
    }

    /**
     * In this test we used a trick: since Joomla uses JTableSession calling
     * JTable::getInstance('session'), it's impossible to mock. So we create a fake user
     * that's surfing in our site writing directly into the session table
     *
     * @group           tableIsCheckedOut
     * @dataProvider    getTestIsCheckedOut
     */
    public function testIsCheckedOut($tableinfo, $test, $check)
    {
        $db    = JFactory::getDbo();
        $table = new FOFTable($tableinfo['table'], $tableinfo['id'], $db);

        if($test['alias'])
        {
            $table->setColumnAlias('locked_by', $test['alias']['lockby']);
            $table->setColumnAlias('locked_on', $test['alias']['lockon']);
        }

        $table->load($test['id']);
        $this->assertEquals($check['return'], $table->isCheckedOut($test['with']), $check['msg']);
    }

	/**
	 * @preventDataLoading
	 */
	public function testIsCheckedOutExcpetion()
    {
        $this->setExpectedException('UnexpectedValueException');

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'bare'));
        $table 		     = FOFTable::getAnInstance('Bare', 'FoftestTable', $config);
        $table->isCheckedOut();
    }

    /**
     * @group           tableCopy
     * @dataProvider    getTestCopy
     */
    public function testCopy($events, $tableinfo, $test, $check)
    {
	    // TODO at the moment the case when onAfterCopy returns false is not covered, since
	    // it simply doens't change anything...

        $db          = JFactory::getDbo();
        $methods     = array('onBeforeCopy', 'onAfterCopy');
        $constr_args = array($tableinfo['table'], $tableinfo['id'], &$db);
        $table = $this->getMock('FOFTable',	$methods, $constr_args,	'',	true, true, true, true);
	    $table->expects($this->any())->method('onBeforeCopy')->will($this->returnValue($events['before']));
	    $table->expects($this->any())->method('onAfterCopy')->will($this->returnValue($events['after']));

	    //$table = new FOFTable($tableinfo['table'], $tableinfo['id'], $db);
	    $table->setAssetKey('com_foftest.foobar');

        if($test['alias'])
        {
            foreach($test['alias'] as $field => $alias)
            {
                $table->setColumnAlias($field, $alias);
            }
        }

        if($test['loadid'])
        {
            $table->load($test['loadid']);
        }

        $rc = $table->copy($test['cids']);
        $this->assertEquals($check['return'], $rc, 'Copy: Wrong return value');

        if($check['more'])
        {
	        $nocopy = 0;

	        // Fields that I should ignore while compariring the two rows
			$skipfields[] = $table->getKeyName();
	        $skipfields[] = $table->getColumnAlias('slug');
	        $skipfields[] = $table->getColumnAlias('asset_id');
	        $skipfields[] = $table->getColumnAlias('created_by');
	        $skipfields[] = $table->getColumnAlias('created_on');
	        $skipfields[] = $table->getColumnAlias('modified_by');
	        $skipfields[] = $table->getColumnAlias('modified_on');

	        // Fields that MUST be different between the two rows
	        $difffields[] = $table->getKeyName();
	        $difffields[] = $table->getColumnAlias('slug');
	        $difffields[] = $table->getColumnAlias('asset_id');
	        $difffields[] = $table->getColumnAlias('created_on');

	        // I "cheat" with the id of copied elements to make my life easier, since I already know
	        // the values that they will get... :)
            foreach($check['cids'] as $original => $copy)
            {
	            // The record shouldn't be copied
	            if(!$copy)
	            {
					$nocopy++;
		            continue;
	            }

                $query = $db->getQuery(true)
                            ->select('*')
                            ->from($tableinfo['table'])
                            ->where($tableinfo['id'].' = '.$original);
                $orig_row = $db->setQuery($query)->loadAssoc();

                $query = $db->getQuery(true)
                            ->select('*')
                            ->from($tableinfo['table'])
                            ->where($tableinfo['id'].' = '.$copy);
                $copy_row = $db->setQuery($query)->loadAssoc();

	            // Create two "working" arrays for testing same and diff fields
	            $orig_row_same = $orig_row;
	            $copy_row_same = $copy_row;
	            $orig_row_diff = $orig_row;
	            $copy_row_diff = $copy_row;

	            // Let's remove fields that are different
	            foreach($orig_row as $field => $value)
	            {
		            if(in_array($field, $skipfields))
		            {
			            unset($orig_row_same[$field]);
			            unset($copy_row_same[$field]);
		            }

		            if(!in_array($field, $difffields))
		            {
			            unset($orig_row_diff[$field]);
			            unset($copy_row_diff[$field]);
		            }
	            }

				$this->assertEquals($orig_row_same, $copy_row_same, 'Copy: Non special fields should be the same');
				$this->assertNotEquals($orig_row_diff, $copy_row_diff, "Copy: Special fields shouldn't be the same");
            }

	        // Let's check if
	        if($nocopy)
	        {
		        $query    = $db->getQuery(true)->select('COUNT(*)')->from($tableinfo['table']);
		        $count    = $db->setQuery($query)->loadResult();
		        $expected = 5 + count($check['cids']) - $nocopy;
		        $this->assertEquals($expected, $count, 'Copy: Wrong total number of items, maybe some unwanted records has been copied?');
	        }
        }
    }

	/**
	 * @group           tablePublish
	 * @dataProvider    getTestPublish
	 */
	public function testPublish($events, $tableinfo, $test, $check)
	{
		$db          = JFactory::getDbo();
		$methods     = array('onBeforePublish');
		$constr_args = array($tableinfo['table'], $tableinfo['id'], &$db);
		$table = $this->getMock('FOFTable',	$methods, $constr_args,	'',	true, true, true, true);
		$table->expects($this->any())->method('onBeforePublish')->will($this->returnValue($events['before']));

		if($test['alias'])
		{
			foreach($test['alias'] as $field => $alias)
			{
				$table->setColumnAlias($field, $alias);
			}
		}

		if($test['loadid'])
		{
			$table->load($test['loadid']);
		}

		$rc = $table->publish($test['cids'], $test['publish'], $test['user']);
		$this->assertEquals($check['return'], $rc, 'Publish: Wrong return value');

		if($check['more'])
		{
			$enabledName = $table->getColumnAlias('enabled');
			$cids        = $test['loadid'] ? (array)$test['loadid'] : (array)$test['cids'];

			// Let's get an indexed array on primary key
			$query = $db->getQuery(true)
						->select(array($table->getKeyName(), $enabledName))
						->from($tableinfo['table'])
						->where($table->getKeyName(). ' IN('.implode(',', $cids).')');

			$rows = $db->setQuery($query)->loadObjectList($table->getKeyName());

			// If there is only record, the publish method should update the table, too
			if(count($check['cids']) == 1 && $test['loadid'])
			{
				$this->assertEquals($test['publish'], $table->$enabledName, 'Publish: wrong value assigned to the table');
			}

			// Is something different from the expected?
			foreach($check['cids'] as $record => $value)
			{
				$this->assertEquals($value, $rows[$record]->$enabledName, 'Publish: record '.$record.' has a wrong publish value in the database');
			}
		}
	}

	/**
	 * @group           tableDelete
	 * @dataProvider    getTestDelete
	 */
	public function testDelete($events, $tableinfo, $test, $check)
	{
		$db          = JFactory::getDbo();
		$methods     = array_keys($events);
		if($test['mockAsset'])  $methods[] = 'getAsset';

		$id          = max($test['loadid'], $test['cid']);
		$constr_args = array($tableinfo['table'], $tableinfo['id'], &$db);
		$table       = $this->getMock('FOFTable', $methods, $constr_args, '', true, true, true, true);

		foreach($events as $event => $return)
		{
			$table->expects($this->any())->method($event)->will($this->returnValue($return));
		}

		if($test['mockAsset'])
		{
			$asset = $this->getMock('JTableAsset', array('delete'), array(&$db));
			$asset->expects($this->any())->method('delete')->will($this->returnValue($test['mockAsset']['return']));
			$table->expects($this->any())->method('getAsset')->will($this->returnValue($asset));
		}

		// Should I check if the asset has been deleted?
		if($check['checkAsset'])
		{
			$query = $db->getQuery(true)->select($table->getColumnAlias('asset_id'))->from($tableinfo['table'])->where($table->getKeyName().' = '.$id);
			$asset_id = $db->setQuery($query)->loadResult();
		}

		// We have to manually provide this info, since we can't use the getInstance method (we have to mock)
		if(isset($test['assetkey']))
		{
			$table->setAssetKey($test['assetkey']);
		}

		if(isset($test['alias']))
		{
			foreach($test['alias'] as $column => $alias)
			{
				$table->setColumnAlias($column, $alias);
			}
		}

		if($test['loadid'])
		{
			$table->load($test['loadid']);
		}

		$rc = $table->delete($test['cid']);
		$this->assertEquals($check['return'], $rc, 'Delete: Wrong return value');

		if($check['more'])
		{
			$query = $db->getQuery(true)
						->select('COUNT(*)')
						->from($tableinfo['table'])
						->where($table->getKeyName().' = '.$id);
			$count = $db->setQuery($query)->loadResult();

			$this->assertEquals($check['count'], $count, 'Delete: Wrong behavior on record under delete');

			if($check['checkAsset'])
			{
				$query = $db->getQuery(true)->select('COUNT(*)')->from('#__assets')->where('id = '.$asset_id);
				$count = $db->setQuery($query)->loadResult();

				// I can use the same variable, since when I delete the record, I want the asset deleted, too
				$this->assertEquals($check['count'], $count, 'Delete: Wrong behavior on record asset under delete');
			}

		}
	}

	/**
	 * @preventDataLoading
	 */
	public function testDeleteException()
	{
		$this->setExpectedException('UnexpectedValueException');

		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobars'));
		$table 		     = FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);
		$table->delete();
	}

	public function testGetUcmCoreAlias()
	{
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));

		$table 		= FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);
		$reflection = new ReflectionClass($table);

		$method  = $reflection->getMethod('getUcmCoreAlias');
		$method->setAccessible(true);

		$table->propertyExist = 'dummy';
		$table->addKnownField('propertyExist');
		$alias = $method->invokeArgs($table, array('propertyExist'));
		$this->assertEquals('propertyExist', $alias, 'Invalid value for existing property');
		$table->removeKnownField('propertyExists');

		$alias = $method->invokeArgs($table, array('propertyDoesNotExist'));
		$this->assertEquals('null', $alias, 'Invalid value for non-existing property');

		$table->testalias = 'aliased property';
		$table->addKnownField('testalias');
		$table->setColumnAlias('testcolumn', 'testalias');
		$alias = $method->invokeArgs($table, array('testcolumn'));
		$this->assertEquals('testalias', $alias, 'Invalid value for aliased property');
	}

	/**
	 * @dataProvider getTestGetContentType
	 */
	public function testGetContentType($option, $view, $expected, $message)
	{
		$config['input'] = new FOFInput(array('option' => $option, 'view' => $view));

		$table = FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);
		$this->assertEquals($expected, $table->getContentType(), $message);
	}

	public function getTestLoadJoined()
	{
		return TableDataprovider::getTestLoadJoined();
	}

    public function getTestBind()
    {
		return TableDataprovider::getTestBind();
    }

    public function getTestStore()
    {
	    return TableDataprovider::getTestStore();
    }

    public function getTestMove()
    {
		return TableDataprovider::getTestMove();
    }

    public function getTestReorder()
    {
		return TableDataprovider::getTestReorder();
    }

    public function getTestCheckout()
    {
	    return TableDataprovider::getTestCheckout();
    }

    public function getTestCheckin()
    {
		return TableDataprovider::getTestCheckin();
    }

    public function getTestIsCheckedOut()
    {
	    return TableDataprovider::getTestIsCheckedOut();
    }

	public function getTestCopy()
	{
		return TableDataprovider::getTestCopy();
	}

	public function getTestPublish()
	{
		return TableDataprovider::getTestPublish();
	}

	public function getTestDelete()
	{
		return TableDataprovider::getTestDelete();
	}

	public function getTestGetContentType()
	{
		return TableDataprovider::getTestGetContentType();
	}
}
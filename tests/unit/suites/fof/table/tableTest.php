<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Inflector
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

class FOFTableTest extends FtestCaseDatabase
{
    protected function setUp()
    {
        parent::setUp();

        FOFPlatform::forceInstance(null);
        FOFTable::forceInstance(null);

    }

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

	public function testRemoveKnownField()
	{
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));

		$table 		= FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);

		$table->addKnownField('foo');
		$table->removeKnownField('foo');

		$known_fields = $this->readAttribute($table, 'knownFields');

		$this->assertNotContains('foo', $known_fields, 'Known fields set differ from defined list');
	}

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

    public function testBindException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'foobar'));
        $table 		     = FOFTable::getAnInstance('Foobar', 'FoftestTable', $config);
        $table->bind('This is a wrong argument');
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

	public function getTestGetContentType()
	{
		$data[] = array('com_foftest', 'foobar', 'com_foftest.foobar', 'Wrong content type');
		$data[] = array('com_foftest', 'foobars', 'com_foftest.foobar', 'Wrong content type');

		return $data;
	}

    public function getTestBind()
    {
        //TODO Create a dataset with "rules", too

        // Check when onBeforeBind is false
        $data[] = array(false, false, array(), array(), array());

        // Check binding with array
        $data[] = array(true, true, array('title' => 'Binded array title'), array(), array(
            array(
                'field' => 'title',
                'value' => 'Binded array title',
                'msg'   => 'Wrong value binded')
            )
        );

        // Check binding with object
        $bind   = new stdClass();
        $bind->title = 'Binded object title';

        $data[] = array(true, true, $bind, array(), array(
            array(
                'field' => 'title',
                'value' => 'Binded object title',
                'msg'   => 'Wrong value binded')
            )
        );

        // Check binding with array and array ignore fields
        $bind   = new stdClass();
        $bind->title = 'Binded object title';
        $bind->slug  = 'Ignored field';

        $data[] = array(true, true, $bind, array('slug'), array(
            array(
                'field' => 'title',
                'value' => 'Binded object title',
                'msg'   => 'Wrong value binded'),
            array(
                'field' => 'slug',
                'value' => '',
                'msg'   => 'Ignored field binded')
            )
        );

        // Check binding with array and string ignore fields
        $bind              = new stdClass();
        $bind->title       = 'Binded object title';
        $bind->slug        = 'Ignored field';
        $bind->created_by  = 'Ignored field';

        $data[] = array(true, true, $bind, 'slug created_by', array(
            array(
                'field' => 'title',
                'value' => 'Binded object title',
                'msg'   => 'Wrong value binded'),
            array(
                'field' => 'slug',
                'value' => '',
                'msg'   => 'Ignored field binded'),
            array(
                'field' => 'created_by',
                'value' => '',
                'msg'   => 'Ignored field binded')
            )
        );

        return $data;
    }

    public function getTestMove()
    {
        // Test vs table not loaded
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 0, 'delta'  => 1, 'where' => ''),
            array('return' => false)
        );

        // Test vs onBeforeMove returns false
        $data[] = array(
            array('before' => false, 'after' => false),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 4, 'delta'  => 0, 'where' => ''),
            array('return' => false)
        );

        // Test vs delta = 0 and onAfterMove returns false
        $data[] = array(
            array('before' => true, 'after' => false),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 4, 'delta'  => 0, 'where' => ''),
            array('return' => false)
        );

        // Test vs delta = 0 and onAfterMove returns true
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 4, 'delta'  => 0, 'where' => ''),
            array('return' => true, 'more' => false)
        );

        // Test vs delta = 1 (everything else ok) inner record
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 4, 'delta'  => 1, 'where' => ''),
            array(
                'return' => true,
                'more'   => true,
                'value'  => 5,
                'msg'    => 'Move() wrong ordering with delta = 1, no where',
                'find'   => array(
                    'id'    => 5,
                    'value' => 4,
                    'msg'   => 'Move() wrong record swapping with delta = 1, no where'
                )
            )
        );

        // Test vs delta = 1 (everything else ok) outer record
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 5, 'delta'  => 1, 'where' => ''),
            array(
                'return' => true,
                'more'   => true,
                'value'  => 5,
                'msg'    => 'Move() wrong ordering with delta = 1, no where'
            )
        );

        // Test vs delta = -1 (everything else ok) inner record
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 4, 'delta'  => -1, 'where' => ''),
            array(
                'return' => true,
                'more'   => true,
                'value'  => 3,
                'msg'    => 'Move() wrong ordering with delta = -1, no where',
                'find'   => array(
                    'id'    => 3,
                    'value' => 4,
                    'msg'   => 'Move() wrong record swapping with delta = -1, no where'
                )

            )
        );

        // Test vs delta = -1 (everything else ok) outer record
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 1, 'delta'  => -1, 'where' => ''),
            array(
                'return' => true,
                'more'   => true,
                'value'  => 1,
                'msg'    => 'Move() wrong ordering with delta = -1, no where'
            )
        );

        // Test vs delta = 1 and where (everything else ok), inner record
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 2, 'delta'  => 1, 'where' => 'enabled = 0'),
            array(
                'return' => true,
                'more'   => true,
                'value'  => 4,
                'msg'    => 'Move() wrong ordering with delta = 1, where enabled = 0',
                'find'   => array(
                    'id'    => 4,
                    'value' => 2,
                    'msg'   => 'Move() wrong record swapping with delta = 1, where enabled = 0'
                )
            )
        );

        // Test vs delta = 1 and where (everything else ok), outer record
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 4, 'delta'  => 1, 'where' => 'enabled = 0'),
            array(
                'return' => true,
                'more'   => true,
                'value'  => 4,
                'msg'    => 'Move() wrong ordering with delta = 1, where enabled = 0',
            )
        );

        // Test vs delta = -1 and where (everything else ok), outer record
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 2, 'delta'  => -1, 'where' => 'enabled = 0'),
            array(
                'return' => true,
                'more'   => true,
                'value'  => 2,
                'msg'    => 'Move() wrong ordering with delta = -1, where enabled = 0',
            )
        );

        // Test vs delta = -1 and where (everything else ok), inner record
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id'     => 4, 'delta'  => -1, 'where' => 'enabled = 0'),
            array(
                'return' => true,
                'more'   => true,
                'value'  => 2,
                'msg'    => 'Move() wrong ordering with delta = 1, where enabled = 0',
                'find'   => array(
                    'id'    => 2,
                    'value' => 4,
                    'msg'   => 'Move() wrong record swapping with delta = 1, where enabled = 0'
                )
            )
        );

        // Test vs delta = 1, using aliases
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
            array('id'     => 4, 'alias' => 'fo_ordering', 'delta'  => 1, 'where' => ''),
            array(
                'return' => true,
                'more'   => true,
                'value'  => 5,
                'msg'    => 'Move() wrong ordering with delta = 1, no where',
                'find'   => array(
                    'id'    => 5,
                    'value' => 4,
                    'msg'   => 'Move() wrong record swapping with delta = 1, no where'
                )
            )
        );

        return $data;
    }

    public function getTestReorder()
    {
        // Test vs onBeforeReorder returns false
        $data[] = array(
            array('before' => false, 'after' => false),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id' => '', 'ordering' => '', 'where' => ''),
            array('return' => false)
        );

        // Test vs reorder, positive number, no where
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id' => 3, 'ordering' => 100, 'where' => ''),
            array(
                'return' => true,
                'more'   => true,
                'msg'    => 'Reorder() wrong reordered recordset with positive number, no where',
                'list'   => array(
                    array('foftest_foobar_id' => 1, 'ordering' => 1),
                    array('foftest_foobar_id' => 2, 'ordering' => 2),
                    array('foftest_foobar_id' => 4, 'ordering' => 3),
                    array('foftest_foobar_id' => 5, 'ordering' => 4),
                    array('foftest_foobar_id' => 3, 'ordering' => 5)
                )
            )
        );

        // Test vs reorder, negative number, no where
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id' => 3, 'ordering' => -100, 'where' => ''),
            array(
                'return' => true,
                'more'   => true,
                'msg'    => 'Reorder() wrong reordered recordset with negative number, no where',
                'list'   => array(
                    array('foftest_foobar_id' => 3, 'ordering' => -100),
                    array('foftest_foobar_id' => 1, 'ordering' => 1),
                    array('foftest_foobar_id' => 2, 'ordering' => 2),
                    array('foftest_foobar_id' => 4, 'ordering' => 3),
                    array('foftest_foobar_id' => 5, 'ordering' => 4)
                )
            )
        );

        // Test vs reorder, positive number, where enabled = 1
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id' => 3, 'ordering' => 100, 'where' => 'enabled = 1'),
            array(
                'return' => true,
                'more'   => true,
                'msg'    => 'Reorder() wrong reordered recordset with positive number, where enabled = 1',
                'list'   => array(
                    array('foftest_foobar_id' => 1, 'ordering' => 1),
                    array('foftest_foobar_id' => 5, 'ordering' => 2),
                    array('foftest_foobar_id' => 3, 'ordering' => 3)
                )
            )
        );

        // Test vs reorder, negative number, where enabled = 1
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array('id' => 3, 'ordering' => -100, 'where' => 'enabled = 1'),
            array(
                'return' => true,
                'more'   => true,
                'msg'    => 'Reorder() wrong reordered recordset with negative number, where enabled = 1',
                'list'   => array(
                    array('foftest_foobar_id' => 3, 'ordering' => -100),
                    array('foftest_foobar_id' => 1, 'ordering' => 1),
                    array('foftest_foobar_id' => 5, 'ordering' => 2)
                )
            )
        );

        // Test vs aliased reorder, positive number, no where
        $data[] = array(
            array('before' => true, 'after' => true),
            array('table'  => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
            array('id' => 3, 'ordering' => 100, 'alias' => 'fo_ordering', 'where' => ''),
            array(
                'return' => true,
                'more'   => true,
                'msg'    => 'Reorder() wrong aliased reordered recordset with positive number, no where',
                'list'   => array(
                    array('id_foobar_aliases' => 1, 'fo_ordering' => 1),
                    array('id_foobar_aliases' => 2, 'fo_ordering' => 2),
                    array('id_foobar_aliases' => 4, 'fo_ordering' => 3),
                    array('id_foobar_aliases' => 5, 'fo_ordering' => 4),
                    array('id_foobar_aliases' => 3, 'fo_ordering' => 5)
                )
            )
        );

        return $data;
    }

    public function getTestCheckout()
    {
        // Test vs table without checkout support
        $data[] = array(
            array('table' => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
            array(
                'loadid' => '',
                'user'   => 99,
                'id'     => 5,
                'alias'  => ''
            ),
            array(
                'return' => true,
                'more'   => false
            )
        );

        // Test vs table, no id given
        $data[] = array(
            array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array(
                'loadid' => '',
                'user'   => 99,
                'id'     => null,
                'alias'  => ''
            ),
            array(
                'return' => false,
                'more'   => false
            )
        );

        // Test vs table, id given
        $data[] = array(
            array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array(
                'loadid' => '',
                'user'   => 99,
                'id'     => 4,
                'alias'  => ''
            ),
            array(
                'return' => true,
                'more'   => true
            )
        );

        // Test vs table, no id given, load it first
        $data[] = array(
            array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array(
                'loadid' => 4,
                'user'   => 99,
                'id'     => null,
                'alias'  => ''
            ),
            array(
                'return' => true,
                'more'   => true
            )
        );

        // Test vs aliased table, no id given, load it first
        $data[] = array(
            array('table' => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
            array(
                'loadid' => 4,
                'user'   => 99,
                'id'     => null,
                'alias'  => array(
                    'lockby' => 'fo_locked_by',
                    'lockon' => 'fo_locked_on'
                )
            ),
            array(
                'return' => true,
                'more'   => true
            )
        );

        return $data;
    }

    public function getTestCheckin()
    {
        // Test vs table without checkin support
        $data[] = array(
            array('table' => 'jos_foftest_bares', 'id' => 'foftest_bare_id'),
            array(
                'loadid' => '',
                'id'     => 5,
                'alias'  => ''
            ),
            array(
                'return' => true,
                'more'   => false
            )
        );

        // Test vs table, no id given
        $data[] = array(
            array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array(
                'loadid' => '',
                'id'     => null,
                'alias'  => ''
            ),
            array(
                'return' => false,
                'more'   => false
            )
        );

        // Test vs table, id given
        $data[] = array(
            array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array(
                'loadid' => '',
                'id'     => 4,
                'alias'  => ''
            ),
            array(
                'return' => true,
                'more'   => true
            )
        );

        // Test vs table, no id given, load it first
        $data[] = array(
            array('table' => 'jos_foftest_foobars', 'id' => 'foftest_foobar_id'),
            array(
                'loadid' => 4,
                'id'     => null,
                'alias'  => ''
            ),
            array(
                'return' => true,
                'more'   => true
            )
        );

        // Test vs aliased table, no id given, load it first
        $data[] = array(
            array('table' => 'jos_foftest_foobaraliases', 'id' => 'id_foobar_aliases'),
            array(
                'loadid' => 4,
                'id'     => null,
                'alias'  => array(
                    'lockby' => 'fo_locked_by',
                    'lockon' => 'fo_locked_on'
                )
            ),
            array(
                'return' => true,
                'more'   => true
            )
        );

        return $data;
    }
}
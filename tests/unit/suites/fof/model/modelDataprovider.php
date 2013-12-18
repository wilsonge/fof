<?php

use org\bovigo\vfs\vfsStream;

abstract class ModelDataprovider
{
	public static function getTestSetIDsFromRequest()
    {
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'id'  => 5,
                'cid' => array()
            ),
            array(
                'id'      => 5,
                'id_list' => array(5)
            )
        );

        $data[] = array(
            array('name' => 'foobars'),
            array(
                'id'  => 0,
                'cid' => array(5, 2)
            ),
            array(
                'id'      => 5,
                'id_list' => array(5, 2)
            )
        );

        // cid vs id => cid wins
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'id'  => 4,
                'cid' => array(2, 3)
            ),
            array(
                'id'      => 2,
                'id_list' => array(2, 3)
            )
        );

        // id vs kid => id wins
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'id'  => 5,
                'cid' => array(),
                'kid' => array(
                    'name'  => 'foftest_foobar_id',
                    'value' => 4
                )
            ),
            array(
                'id'      => 5,
                'id_list' => array(5)
            )
        );

        // cid vs kid => cid wins
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'id'  => 0,
                'cid' => array(7, 8),
                'kid' => array(
                    'name'  => 'foftest_foobar_id',
                    'value' => 4
                )
            ),
            array(
                'id'      => 7,
                'id_list' => array(7, 8)
            )
        );

        $data[] = array(
            array('name' => 'foobars'),
            array(
                'id'  => 0,
                'cid' => array(),
                'kid' => array(
                    'name'  => 'foftest_foobar_id',
                    'value' => 4
                )
            ),
            array(
                'id'      => 4,
                'id_list' => array(4)
            )
        );

        return $data;
    }

	public static function getTestSetId()
	{
		$data[] = array(1);
		$data[] = array('12');
		$data[] = array(0);
		$data[] = array('0');
        $data[] = array(array(4));
        $data[] = array(array(4, 7));

        return $data;
	}

	public static function getTestSetIdException()
	{
		$data[] = array(new stdClass());

		return $data;
	}

    public static function getTestSetIds()
    {
        // Correct behavior
        $data[] = array(
            array(1, '2'),
            array('id' => 1, 'id_list' => array(1, 2))
        );

        // Wrong data
        $data[] = array(
            array(array(2, 2)),
            array('id' => 0, 'id_list' => array())
        );


        $data[] = array(
            array(),
            array('id' => 0, 'id_list' => array())
        );

        // Wrong data
        $data[] = array(
            array(),
            array('id' => 0, 'id_list' => array())
        );

        $data[] = array(
            1,
            array('id' => 0,'id_list' => array())
        );

        $data[] = array(
            '1',
            array('id' => 0,'id_list' => array())
        );

        $data[] = array(
            new stdClass(),
            array('id' => 0,'id_list' => array())
        );

        return $data;
    }

    public static function getTestGetItem()
    {
        // Load setting the model id
        $data[] = array(
            array('name' => 'Foobars'),
            array('setid' => 2, 'id' => null),
            array(),
            array('title' => 'Second row')
        );

        // Load by passing an id
        $data[] = array(
            array('name' => 'Foobars'),
            array('setid' => 0, 'id' => 2),
            array(),
            array('title' => 'Second row')
        );

        // No id at all
        $data[] = array(
            array('name' => 'Foobars'),
            array('setid' => 0, 'id' => 0),
            array(),
            array('title' => null)
        );

        // No id at all, but data from the session (ie a record failed the validation)
        $data[] = array(
            array('name' => 'Foobars'),
            array('setid' => 0, 'id' => 0),
            serialize(array('title' => 'Title from session')),
            array('title' => 'Title from session', 'foftest_foobar_id' => 0)
        );

        // Test vs data coming from session (record inside the db)
        $data[] = array(
            array('name' => 'Foobars'),
            array('setid' => null, 'id' => 2),
            serialize(array('foftest_foobar_id' => 2, 'title' => 'Title from session')),
            array('title' => 'Title from session')
        );

        return $data;
    }

    public static function getTestBuildQuery()
    {
        $db = JFactory::getDbo();

        // SELECT `#__foftest_foobars`.* FROM `#__foftest_foobars` ORDER BY `foftest_foobar_id` ASC
        $data[] = array(
            array('name' => 'foobars'),
            array('aliasTable' => '', 'overrideLimits' => false),
            array('query' => $db->getQuery(true)
                                ->select($db->qn('#__foftest_foobars').'.*')
                                ->from($db->qn('#__foftest_foobars'))
                                ->order($db->qn('foftest_foobar_id').' ASC')
            )
        );

        // SELECT `#__foftest_foobars`.* FROM `#__foftest_foobars`
        $data[] = array(
            array('name' => 'foobars'),
            array('aliasTable' => '', 'overrideLimits' => true),
            array('query' => $db->getQuery(true)
                    ->select($db->qn('#__foftest_foobars').'.*')
                    ->from($db->qn('#__foftest_foobars'))
            )
        );

        // SELECT `foo_alias`.* FROM `#__foftest_foobars` AS `foo_alias` ORDER BY `foo_alias`.`foftest_foobar_id` ASC
        $data[] = array(
            array('name' => 'foobars'),
            array('aliasTable' => 'foo_alias', 'overrideLimits' => false),
            array('query' => $db->getQuery(true)
                                ->select($db->qn('foo_alias').'.*')
                                ->from($db->qn('#__foftest_foobars').' AS '.$db->qn('foo_alias'))
                                ->order($db->qn('foo_alias').'.'.$db->qn('foftest_foobar_id').' ASC')
            )
        );

        return $data;
    }

    public static function getTestGetHash()
    {
        $data[] = array(
            array('name' => 'foobars'),
            array('tmpInstance' => false),
            array('hash' => 'com_foftest.foobars.')
        );

        $data[] = array(
            array('name' => 'foobar'),
            array('tmpInstance' => false),
            array('hash' => 'com_foftest.foobars.')
        );

		// Edit by Nicholas: The hash is not used in temporary model instances (using getTmpInstance), as we are
		// explicitly DO NOT want the state data to persist.
		/**
        $data[] = array(
            array('name' => 'foobars'),
            array('tmpInstance' => true),
            array('hash' => 'com_foftest.foobars.')
        );

        $data[] = array(
            array('name' => 'foobar'),
            array('tmpInstance' => true),
            array('hash' => 'com_foftest.foobars.')
        );
        /**/

        return $data;
    }

    public static function getTestGetList()
    {
        $db = JFactory::getDbo();

        // Standard query
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'query'      => $db->getQuery(true)->select('foftest_foobar_id, title, slug')->from('#__foftest_foobars'),
                'limitstart' => 0,
                'limit'      => 0,
                'group'      => '',
                'callback'   => function(&$array){}
            ),
            array(
                'list'       => array(
                    0 => (object) array('foftest_foobar_id' => 1, 'title' => 'Guinea Pig row', 'slug' => 'guinea-pig-row'),
                    1 => (object) array('foftest_foobar_id' => 2, 'title' => 'Second row', 'slug' => 'second-row'),
                    2 => (object) array('foftest_foobar_id' => 3, 'title' => 'Third row', 'slug' => 'third-row'),
                    3 => (object) array('foftest_foobar_id' => 4, 'title' => 'Fourth row', 'slug' => 'fourth-row'),
                    4 => (object) array('foftest_foobar_id' => 5, 'title' => 'Locked record', 'slug' => 'locked-record'),
                )
            )
        );

        // String query
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'query'      => (string) $db->getQuery(true)->select('foftest_foobar_id, title, slug')->from('#__foftest_foobars'),
                'limitstart' => 0,
                'limit'      => 0,
                'group'      => '',
                'callback'   => function(&$array){}
            ),
            array(
                'list'       => array(
                    0 => (object) array('foftest_foobar_id' => 1, 'title' => 'Guinea Pig row', 'slug' => 'guinea-pig-row'),
                    1 => (object) array('foftest_foobar_id' => 2, 'title' => 'Second row', 'slug' => 'second-row'),
                    2 => (object) array('foftest_foobar_id' => 3, 'title' => 'Third row', 'slug' => 'third-row'),
                    3 => (object) array('foftest_foobar_id' => 4, 'title' => 'Fourth row', 'slug' => 'fourth-row'),
                    4 => (object) array('foftest_foobar_id' => 5, 'title' => 'Locked record', 'slug' => 'locked-record'),
                )
            )
        );

        // Standard query with limit
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'query'      => $db->getQuery(true)->select('foftest_foobar_id, title, slug')->from('#__foftest_foobars'),
                'limitstart' => 0,
                'limit'      => 3,
                'group'      => '',
                'callback'   => function(&$array){}
            ),
            array(
                'list'       => array(
                    0 => (object) array('foftest_foobar_id' => 1, 'title' => 'Guinea Pig row', 'slug' => 'guinea-pig-row'),
                    1 => (object) array('foftest_foobar_id' => 2, 'title' => 'Second row', 'slug' => 'second-row'),
                    2 => (object) array('foftest_foobar_id' => 3, 'title' => 'Third row', 'slug' => 'third-row')
                )
            )
        );

        // Standard query with limit and limitstart
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'query'      => $db->getQuery(true)->select('foftest_foobar_id, title, slug')->from('#__foftest_foobars'),
                'limitstart' => 2,
                'limit'      => 3,
                'group'      => '',
                'callback'   => function(&$array){}
            ),
            array(
                'list'       => array(
                    0 => (object) array('foftest_foobar_id' => 3, 'title' => 'Third row', 'slug' => 'third-row'),
                    1 => (object) array('foftest_foobar_id' => 4, 'title' => 'Fourth row', 'slug' => 'fourth-row'),
                    2 => (object) array('foftest_foobar_id' => 5, 'title' => 'Locked record', 'slug' => 'locked-record'),
                )
            )
        );

        // Standard query with groups
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'query'      => $db->getQuery(true)->select('foftest_foobar_id, title, slug')->from('#__foftest_foobars'),
                'limitstart' => 0,
                'limit'      => 0,
                'group'      => 'slug',
                'callback'   => function(&$array){}
            ),
            array(
                'list'       => array(
                    'guinea-pig-row' => (object) array('foftest_foobar_id' => 1, 'title' => 'Guinea Pig row', 'slug' => 'guinea-pig-row'),
                    'second-row'     => (object) array('foftest_foobar_id' => 2, 'title' => 'Second row', 'slug' => 'second-row'),
                    'third-row'      => (object) array('foftest_foobar_id' => 3, 'title' => 'Third row', 'slug' => 'third-row'),
                    'fourth-row'     => (object) array('foftest_foobar_id' => 4, 'title' => 'Fourth row', 'slug' => 'fourth-row'),
                    'locked-record'  => (object) array('foftest_foobar_id' => 5, 'title' => 'Locked record', 'slug' => 'locked-record'),
                )
            )
        );

        // Let's manipulate the result array
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'query'      => $db->getQuery(true)->select('foftest_foobar_id, title, slug')->from('#__foftest_foobars'),
                'limitstart' => 0,
                'limit'      => 0,
                'group'      => '',
                'callback'   => function(&$array){foreach($array as $item){ $item->title = $item->title.'XXX';}}
            ),
            array(
                'list'       => array(
                    0 => (object) array('foftest_foobar_id' => 1, 'title' => 'Guinea Pig rowXXX', 'slug' => 'guinea-pig-row'),
                    1 => (object) array('foftest_foobar_id' => 2, 'title' => 'Second rowXXX', 'slug' => 'second-row'),
                    2 => (object) array('foftest_foobar_id' => 3, 'title' => 'Third rowXXX', 'slug' => 'third-row'),
                    3 => (object) array('foftest_foobar_id' => 4, 'title' => 'Fourth rowXXX', 'slug' => 'fourth-row'),
                    4 => (object) array('foftest_foobar_id' => 5, 'title' => 'Locked recordXXX', 'slug' => 'locked-record'),
                )
            )
        );

        return $data;
    }

    public static function getTestGetItemList()
    {
        $db = JFactory::getDbo();

        $data[] = array(
            array('name' => 'foobars'),
            array(
                'query'      => $db->getQuery(true)->select('foftest_foobar_id, title, slug')->from('#__foftest_foobars'),
                'limitstart' => 0,
                'limit'      => 0,
                'group'      => '',
                'override'   => false
            ),
            array(
                'list'       => array(
                    0 => (object) array('foftest_foobar_id' => 1, 'title' => 'Guinea Pig row', 'slug' => 'guinea-pig-row'),
                    1 => (object) array('foftest_foobar_id' => 2, 'title' => 'Second row', 'slug' => 'second-row'),
                    2 => (object) array('foftest_foobar_id' => 3, 'title' => 'Third row', 'slug' => 'third-row'),
                    3 => (object) array('foftest_foobar_id' => 4, 'title' => 'Fourth row', 'slug' => 'fourth-row'),
                    4 => (object) array('foftest_foobar_id' => 5, 'title' => 'Locked record', 'slug' => 'locked-record'),
                )
            )
        );

        $data[] = array(
            array('name' => 'foobars'),
            array(
                'query'      => $db->getQuery(true)->select('foftest_foobar_id, title, slug')->from('#__foftest_foobars'),
                'limitstart' => 1,
                'limit'      => 2,
                'group'      => '',
                'override'   => false
            ),
            array(
                'list'       => array(
                    0 => (object) array('foftest_foobar_id' => 2, 'title' => 'Second row', 'slug' => 'second-row'),
                    1 => (object) array('foftest_foobar_id' => 3, 'title' => 'Third row', 'slug' => 'third-row'),
                )
            )
        );

        // Overriding the limits (limitstart and limit set)
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'query'      => $db->getQuery(true)->select('foftest_foobar_id, title, slug')->from('#__foftest_foobars'),
                'limitstart' => 1,
                'limit'      => 2,
                'group'      => '',
                'override'   => true
            ),
            array(
                'list'       => array(
                    0 => (object) array('foftest_foobar_id' => 1, 'title' => 'Guinea Pig row', 'slug' => 'guinea-pig-row'),
                    1 => (object) array('foftest_foobar_id' => 2, 'title' => 'Second row', 'slug' => 'second-row'),
                    2 => (object) array('foftest_foobar_id' => 3, 'title' => 'Third row', 'slug' => 'third-row'),
                    3 => (object) array('foftest_foobar_id' => 4, 'title' => 'Fourth row', 'slug' => 'fourth-row'),
                    4 => (object) array('foftest_foobar_id' => 5, 'title' => 'Locked record', 'slug' => 'locked-record'),
                )
            )
        );

        $data[] = array(
            array('name' => 'foobars'),
            array(
                'query'      => $db->getQuery(true)->select('foftest_foobar_id, title, slug')->from('#__foftest_foobars'),
                'limitstart' => 1,
                'limit'      => 2,
                'group'      => 'slug',
                'override'   => false
            ),
            array(
                'list'       => array(
                    'second-row' => (object) array('foftest_foobar_id' => 2, 'title' => 'Second row', 'slug' => 'second-row'),
                    'third-row' => (object) array('foftest_foobar_id' => 3, 'title' => 'Third row', 'slug' => 'third-row'),
                )
            )
        );

        return $data;
    }

    public static function getTestCopy()
    {
        // Everything fine
        $data[] = array(
            array(
                'id_list'  => array(2),
                'copy'     => true,
                'error'    => '',
                'onBefore' => true,
                'onAfter'  => true
            ),
            array('return' => true)
        );

        // Empty id_list
        $data[] = array(
            array(
                'id_list'  => array(),
                'copy'     => true,
                'error'    => '',
                'onBefore' => true,
                'onAfter'  => true
            ),
            array('return' => true)
        );

        // Wrong type id_list
        $data[] = array(
            array(
                'id_list'  => '',
                'copy'     => true,
                'error'    => '',
                'onBefore' => true,
                'onAfter'  => true
            ),
            array('return' => true)
        );

        // Copy returns an error
        $data[] = array(
            array(
                'id_list'  => array(2, 3),
                'copy'     => false,
                'error'    => 'Copy returned false',
                'onBefore' => true,
                'onAfter'  => true
            ),
            array('return' => false)
        );

        // onBeforeCopy returns an error
        $data[] = array(
            array(
                'id_list'  => array(2, 3),
                'copy'     => false,
                'error'    => '',
                'onBefore' => false,
                'onAfter'  => true
            ),
            array('return' => false)
        );

        // onBeforeCopy returns an error (copy would be ok)
        $data[] = array(
            array(
                'id_list'  => array(2, 3),
                'copy'     => true,
                'error'    => '',
                'onBefore' => false,
                'onAfter'  => true
            ),
            array('return' => false)
        );

        // onAfterCopy returns an error (copy would be ok)
        $data[] = array(
            array(
                'id_list'  => array(2, 3),
                'copy'     => true,
                'error'    => '',
                'onBefore' => true,
                'onAfter'  => false
            ),
            array('return' => true)
        );

        return $data;
    }

    public static function getTestDelete()
    {
        // Everything fine
        $data[] = array(
            array(
                'id_list'  => array(2),
                'delete'   => true,
                'error'    => '',
                'onBefore' => true,
                'onAfter'  => true
            ),
            array('return' => true)
        );

        // Empty id_list
        $data[] = array(
            array(
                'id_list'  => array(),
                'delete'   => true,
                'error'    => '',
                'onBefore' => true,
                'onAfter'  => true
            ),
            array('return' => true)
        );

        // Wrong type id_list
        $data[] = array(
            array(
                'id_list'  => '',
                'delete'   => true,
                'error'    => '',
                'onBefore' => true,
                'onAfter'  => true
            ),
            array('return' => true)
        );

        // Delete returns an error
        $data[] = array(
            array(
                'id_list'  => array(2, 3),
                'delete'   => false,
                'error'    => 'Delete returned false',
                'onBefore' => true,
                'onAfter'  => true
            ),
            array('return' => false)
        );

        // onBeforeDelete returns an error
        $data[] = array(
            array(
                'id_list'  => array(2, 3),
                'delete'   => false,
                'error'    => '',
                'onBefore' => false,
                'onAfter'  => true
            ),
            array('return' => true)
        );

        // onBeforeDelete returns an error just once (delete fails)
        $data[] = array(
            array(
                'id_list'  => array(2, 3),
                'delete'   => false,
                'beforeFailsOnce' => true,
                'error'    => '',
                'onBefore' => false,
                'onAfter'  => true
            ),
            array('return' => false)
        );

        // onBeforeDelete returns an error just once (delete works)
        $data[] = array(
            array(
                'id_list'  => array(2, 3),
                'delete'   => true,
                'beforeFailsOnce' => true,
                'error'    => '',
                'onBefore' => false,
                'onAfter'  => true
            ),
            array('return' => true)
        );

        // onBeforeDelete returns an error (copy would be ok)
        $data[] = array(
            array(
                'id_list'  => array(2, 3),
                'delete'   => true,
                'error'    => '',
                'onBefore' => false,
                'onAfter'  => true
            ),
            array('return' => true)
        );

        // onAfterDelete returns an error (copy would be ok)
        $data[] = array(
            array(
                'id_list'  => array(2, 3),
                'delete'   => true,
                'error'    => '',
                'onBefore' => true,
                'onAfter'  => false
            ),
            array('return' => true)
        );

        return $data;
    }

    public static function getTestGetTable()
    {
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'name'   => 'Foobar', 'prefix'  => 'FoftestTable', 'options' => array(),
                'create' => array(
                    'name'    => 'Foobar',
                    'prefix'  => 'FoftestTable',
                    'options' => ''
                )
            )
        );

        $data[] = array(
            array('name' => 'foobars'),
            array(
                'name'   => 'Foobar', 'prefix'  => null, 'options' => array(),
                'create' => array(
                    'name'    => 'Foobar',
                    'prefix'  => 'FoftestTable',
                    'options' => ''
                )
            )
        );

        $data[] = array(
            array('name' => 'foobars'),
            array(
                'name'   => '', 'prefix'  => null, 'options' => array(),
                'create' => array(
                    'name'    => 'foobar',
                    'prefix'  => 'FoftestTable',
                    'options' => ''
                )
            )
        );

        $data[] = array(
            array('name' => 'foobars'),
            array(
                'name'   => '', 'prefix'  => null, 'options' => array(), 'wipeTable' => true,
                'create' => array(
                    'name'    => 'foobar',
                    'prefix'  => 'FoftestTable',
                    'options' => ''
                )
            )
        );

        return $data;
    }

    public static function getTestCreateTable()
    {
        // Standard call
        $data[] = array(
            array('name' => 'Foobars'),
            array('name' => 'Foobar', 'prefix' => 'FoftestTable', 'config' => array()),
            array('name' => 'Foobar', 'prefix' => 'FoftestTable')
        );

        // Standard call - with dbo
        $data[] = array(
            array('name' => 'Foobars'),
            array('name' => 'Foobar', 'prefix' => 'FoftestTable', 'config' => array(), 'loadDbo' => true),
            array('name' => 'Foobar', 'prefix' => 'FoftestTable')
        );

        // Wrong configuration param
        $data[] = array(
            array('name' => 'Foobars'),
            array('name' => 'Foobar', 'prefix' => 'FoftestTable', 'config' => ''),
            array('name' => 'Foobar', 'prefix' => 'FoftestTable')
        );

        // Wrong configuration param (2)
        $data[] = array(
            array('name' => 'Foobars'),
            array('name' => 'Foobar', 'prefix' => 'FoftestTable', 'config' => new stdClass()),
            array('name' => 'Foobar', 'prefix' => 'FoftestTable')
        );

        // Wrong table name and table prefix
        $data[] = array(
            array('name' => 'Foobars'),
            array('name' => '$$$Foobar', 'prefix' => '$$$$$$$$FoftestTable', 'config' => array()),
            array('name' => 'Foobar', 'prefix' => 'FoftestTable')
        );

        return $data;
    }

    public static function getTestFindFormFilename()
    {
        $origpaths = array(
            JPATH_ROOT.'/administrator/templates/system/foobars/',
            JPATH_ROOT.'/administrator/templates/system/foobar/',
            JPATH_ROOT.'/administrator/components/com_foftest/views/foobars/tmpl',
            JPATH_ROOT.'/administrator/components/com_foftest/views/foobar/tmpl',
            JPATH_ROOT.'/components/com_foftest/views/foobars/tmpl',
            JPATH_ROOT.'/components/com_foftest/views/foobar/tmpl',
            JPATH_ROOT.'/components/com_foftest/models/forms',
            JPATH_ROOT.'/administrator/components/com_foftest/models/forms',
        );

        foreach($origpaths as &$path)
        {
            $path  = trim(str_replace('\\', '/', $path), '/');
        }

        // Form not found
        $paths  = $origpaths;
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths
            ),
            array('form' => false)
        );

        // Form found in several different places
        // --- START ---
        $paths     = $origpaths;
        $paths[0] .= '/form_browse.xml';
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths
            ),
            array('form' => vfsStream::url('root/'.$paths[0]))
        );

        $paths     = $origpaths;
        $paths[1] .= '/form_browse.xml';
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths
            ),
            array('form' => vfsStream::url('root/'.$paths[1]))
        );

        $paths     = $origpaths;
        $paths[2] .= '/form_browse.xml';
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths
            ),
            array('form' => vfsStream::url('root/'.$paths[2]))
        );

        $paths     = $origpaths;
        $paths[3] .= '/form_browse.xml';
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths
            ),
            array('form' => vfsStream::url('root/'.$paths[3]))
        );

        $paths     = $origpaths;
        $paths[4] .= '/form_browse.xml';
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths
            ),
            array('form' => vfsStream::url('root/'.$paths[4]))
        );

        $paths     = $origpaths;
        $paths[5] .= '/form_browse.xml';
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths
            ),
            array('form' => vfsStream::url('root/'.$paths[5]))
        );

        $paths     = $origpaths;
        $paths[6] .= '/form_browse.xml';
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths
            ),
            array('form' => vfsStream::url('root/'.$paths[6]))
        );

        $paths     = $origpaths;
        $paths[7] .= '/form_browse.xml';
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths
            ),
            array('form' => vfsStream::url('root/'.$paths[7]))
        );
        // --- END ---

        // Form with specific joomla suffix
        $paths     = $origpaths;
        $paths[2] .= '/form_browse.j32.xml';
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths,
                'suffix'    => array('.j32', '.j3')
            ),
            array('form' => vfsStream::url('root/'.$paths[2]))
        );

        $paths     = $origpaths;
        $paths[2] .= '/form_browse.j3.xml';
        $data[] = array(
            array('name' => 'Foobars'),
            array(
                'form_name' => 'form_browse',
                'structure' => self::createArrayDir($paths),
                'paths'     => $paths,
                'suffix'    => array('.j32', '.j3')
            ),
            array('form' => vfsStream::url('root/'.$paths[2]))
        );


        return $data;
    }

    protected static function createArrayDir($paths)
    {
        $tree = array();
        foreach ($paths as $path) {
            $pathParts = explode('/', $path);
            $subTree   = array(array_pop($pathParts));

            if(strpos($subTree[0], '.') !== false)
            {
                $subTree = array($subTree[0] => '');
            }

            foreach (array_reverse($pathParts) as $dir) {
                $subTree = array($dir => $subTree);
            }
            $tree = array_merge_recursive($tree, $subTree);
        }

        return $tree;
    }
}
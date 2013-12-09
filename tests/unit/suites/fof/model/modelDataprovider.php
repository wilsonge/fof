<?php

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
}
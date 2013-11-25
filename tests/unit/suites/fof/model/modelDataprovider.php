<?php

abstract class ModelDataprovider
{
	public static function getTestAddIncludePath()
	{
		// Adding a string path
		$data[] = array(
			array('path' => 'models/foobars.php', 'prefix' => 'FOFModel'),
			array('return' => array('models/foobars.php'))
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
}
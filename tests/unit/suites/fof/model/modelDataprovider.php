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
}
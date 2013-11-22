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
}
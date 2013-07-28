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
}
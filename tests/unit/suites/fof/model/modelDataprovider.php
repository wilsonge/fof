<?php

abstract class ModelDataprovider
{
	public static function getTestAddIncludePath()
	{
		// Adding a string path
		/*$data[] = array(
			array('path' => 'models/foobars.php', 'prefix' => 'FOFModel'),
			array('paths' => array(
				'models/foobars.php'
			))
		);*/
		$data[] = array(
			array('path' => '', 'prefix' => ''),
			array('paths' => '')
		);

		return $data;
	}
}
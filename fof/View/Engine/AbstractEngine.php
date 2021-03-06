<?php
/**
 * Created by PhpStorm.
 * User: nikosdion
 * Date: 28/1/15
 * Time: 19:49
 */

namespace FOF30\View\Engine;


use FOF30\View\View;

abstract class AbstractEngine implements EngineInterface
{
	/** @var   View  The view we belong to */
	protected $view = null;

	/**
	 * Public constructor
	 *
	 * @param   View  $view  The view we belong to
	 */
	public function __construct(View $view)
	{
		$this->view = $view;
	}

	/**
	 * Get the include path for a parsed view template
	 *
	 * @param   string  $path         The path to the view template
	 * @param   array   $forceParams  Any additional information to pass to the view template engine
	 *
	 * @return  array  Content evaluation information
	 */
	public function get($path, array $forceParams = array())
	{
		return array(
			'type' => 'raw',
			'content' => ''
		);
	}
}
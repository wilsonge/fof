<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Factory\Scaffolding\Controller;

use FOF30\Container\Container;
use FOF30\Factory\Magic\ControllerFactory;

/**
 * Scaffolding Builder
 *
 * @package FOF30\Factory\Scaffolding
 */
class Builder
{
	/** @var  \FOF30\Container\Container  The container we belong to */
	protected $container = null;

	/**
	 * Create the scaffolding builder instance
	 *
	 * @param \FOF30\Container\Container $c
	 */
	public function __construct(Container $c)
	{
		$this->container = $c;
	}

	/**
	 * Make a new scaffolding document
	 *
	 * @param   string  $requestedClass     The requested class, with full qualifier ie Myapp\Site\Controller\Foobar
	 * @param   string  $viewName           The name of the view linked to this controller
	 *
	 * @return  string|null  The XML source or null if we can't make a scaffolding XML
	 */
	public function make($requestedClass, $viewName)
	{
        // Class already exists? Stop here
		if (class_exists($requestedClass))
        {
            return true;
        }

        // I have to magically create the controller class
        $magic         = new ControllerFactory($this->container);
        $fofController = $magic->make($viewName);

		/** @var ErectorInterface $erector */
        $erector = new ControllerErector($this, $fofController, $viewName);
		$erector->build();

        if(!class_exists($requestedClass))
        {
            return false;
        }

        return true;
	}
    
	/**
	 * Gets the container this builder belongs to
	 *
	 * @return Container
	 */
	public function getContainer()
	{
		return $this->container;
	}
}
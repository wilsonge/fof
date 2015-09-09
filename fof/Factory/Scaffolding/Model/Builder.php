<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Factory\Scaffolding\Model;

use FOF30\Container\Container;
use FOF30\Factory\Magic\ModelFactory;

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
	 * @return  bool    True on success, false otherwise
	 */
	public function make($requestedClass, $viewName)
	{
        // Class already exists? Stop here
		if (class_exists($requestedClass))
        {
            return true;
        }

        // I have to magically create the model class
        $magic    = new ModelFactory($this->container);
        $fofModel = $magic->make($viewName);

		/** @var ErectorInterface $erector */
        $erector = new ModelErector($this, $fofModel, $viewName);
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
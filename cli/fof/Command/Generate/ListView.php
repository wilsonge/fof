<?php

namespace FOF30\Generator\Command\Generate;
use FOF30\Generator\Command\Command as Command;
use FOF30\Factory\Scaffolding\Builder as ScaffoldingBuilder;
use FOF30\Container\Container as Container;

class ListView extends Command {

	public function execute($composer, $input) {

		$this->setDevServer();

		// Get the view
		$args = $input->args;
		$command = array_shift($args);
		$view = array_shift($args);

		if (!$view) 
		{
			$this->out("Syntax: fof generate view list <name>");
			exit();
		}

		// Backend or frontend?
		$backend = !$input->get('frontend', false);

		// We do have a composer file, so we can start working
		$composer->extra = $composer->extra ? $composer->extra : array('fof' => array());
		$composer->extra->fof = $composer->extra->fof ? $composer->extra->fof : array();

		$component = $composer->extra->fof->name;

		try {
			$container = Container::getInstance($component);
			$container->factory->setSaveScaffolding(true);

			\JFactory::getApplication()->setAdmin($backend);

			$scaffolding = new ScaffoldingBuilder($container);
			$scaffolding->make('form.default', $view);

			$this->out($backend ? "Backend" : "Frontend" . " browse view for " . $view . ' created!');

		} catch(Exception $e) {
			if ($e instanceof \FOF30\Model\DataModel\Exception\NoTableColumns) {
				$this->out("FOF cannot find a database table for " . $view . '. It should be name #__' . $component . '_' . $container->inflector->pluralize($view));
				exit();
			}

			$this->out($e);
			exit();
		}
	}

}
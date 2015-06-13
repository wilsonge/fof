<?php

namespace FOF30\Generator\Command\Generate;
use FOF30\Generator\Command\Command as Command;

class Views extends ViewBase {

	public function execute($composer, $input) {
		$this->createView($composer, $input, 'default');
		$this->createView($composer, $input, 'form');
		$this->createView($composer, $input, 'item');
	}

}
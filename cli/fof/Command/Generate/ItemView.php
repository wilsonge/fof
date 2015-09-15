<?php

namespace FOF30\Generator\Command\Generate;

use FOF30\Generator\Command\Command as Command;

class ItemView extends ViewBase
{
	public function execute($composer, $input)
    {
		$this->createView($composer, $input, 'item');
	}
}
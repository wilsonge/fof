<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Generator\Command\Generate;

use FOF30\Generator\Command\Command as Command;

class FormView extends ViewBase
{
	public function execute($composer, $input)
    {
		$this->createView($composer, $input, 'form');
	}
}
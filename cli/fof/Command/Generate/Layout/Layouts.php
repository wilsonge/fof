<?php
/**
 * @package     FOF
 * @copyright   2010-2015 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Generator\Command\Generate\Layout;

use FOF30\Generator\Command\Command as Command;

class Layouts extends LayoutBase
{
	public function execute()
    {
		$this->createView($this->composer, $this->input, 'default');
		$this->createView($this->composer, $this->input, 'form');
		$this->createView($this->composer, $this->input, 'item');
	}
}
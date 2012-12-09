<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class FOFFormHeaderField extends FOFFormHeader
{
	protected function getHeader()
	{
		$sortable = ($this->element['sortable'] != 'false');

		$label = $this->getLabel();
		
		if ($sortable)
		{
			$view = $this->form->getView();
			return JHTML::_('grid.sort', $label, $this->name, $view->lists->order_Dir, $view->lists->order);
		}
		else
		{
			return JText::_($label);
		}
	}
}
<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

/**
 * FrameworkOnFramework HTML Form Edit class
 */

class FOFViewForm extends FOFViewHtml
{
	protected function onAdd($tpl = null)
	{
		JRequest::setVar('hidemainmenu', true);
		$model = $this->getModel();
		$this->assign( 'item',		$model->getItem() );
		$this->assign( 'form',		$model->getForm() );
		return true;
	}
}
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
		// Hide the main menu
		JRequest::setVar('hidemainmenu', true);
		
		// Get the model
		$model	= $this->getModel();
		
		// Get the form
		$form	= $model->getForm();
		
		// Load CSS and Javascript files defined in the form
		$form->loadCSSFiles();
		$form->loadJSFiles();
		
		// Assign the item and form to the view
		$this->assign( 'item',		$model->getItem() );
		$this->assign( 'form',		$form );
		return true;
	}
}
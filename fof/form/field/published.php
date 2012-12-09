<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

if(!class_exists('JFormFieldList')) {
	require_once JPATH_LIBRARIES.'/joomla/form/fields/list.php';
}

/**
 * Form Field class for FOF
 * Supports a generic list of options.
 *
 * @since       2.0
 */
class FOFFormFieldList extends JFormFieldList implements FOFFormField
{
	protected $static;
	
	protected $repeatable;
	
	/** @var int A monotonically increasing number, denoting the row number in a repeatable view */
	public $rowid;
	
	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   2.0
	 */
	public function __get($name)
	{
		switch($name) {
			case 'static':
				if(empty($this->static)) {
					$this->static = $this->getStatic();
				}

				return $this->static;
				break;
				
			case 'repeatable':
				if(empty($this->repeatable)) {
					$this->repeatable = $this->getRepeatable();
				}

				return $this->static;
				break;
				
			default:
				return parent::__get($name);
		}
	}
	
	protected function getOptions()
	{
		$options = parent::getOptions();
		
		if (!empty($options))
		{
			return $options;
		}
		
		// If no custom options were defined let's figure out which ones of the
		// defaults we shall use...
		
		$stack = array();
		
		if($this->element['show_published'] == 'false')
		{
			// Do not add published
		}
		else
		{
			$stack['JPUBLISHED'] = 1;
		}
		
		if($this->element['show_unpublished'] == 'false')
		{
			// Do not add unpublished
		}
		else
		{
			$stack['JUNPUBLISHED'] = 1;
		}
		
		if($this->element['show_archived'] == 'true')
		{
			$stack['JARCHIVED'] = 2;
		}
		
		if($this->element['show_trashed'] == 'true')
		{
			$stack['JTRASHED'] = -2;
		}
		
		if (empty($stack))
		{
			return $options;
		}
		
		$options = array();
		
		foreach($stack as $label => $value)
		{
			$options[] = JHtml::_(
				'select.option', $value,
				JText::alt($label, preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text'
			);
		}
		
		return $options;
	}
	
	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 * 
	 * @since 2.0
	 */
	public function getRepeatable() {
		if (!($this->item instanceof FOFTable))
		{
			throw new Exception(__CLASS__.' needs a FOFTable to act upon');
		}
		
		// Initialise
		$prefix			= '';
		$checkbox		= 'cb';
		$publish_up		= null;
		$publish_down	= null;
		$enabled		= true;
		
		// Get options
		if($this->element['prefix'])
		{
			$prefix = (string) $this->element['prefix'];
		}
		
		if($this->element['checkbox'])
		{
			$checkbox = (string) $this->element['checkbox'];
		}
		
		if($this->element['publish_up'])
		{
			$publish_up = (string) $this->element['publish_up'];
		}
		
		if($this->element['publish_down'])
		{
			$publish_down = (string) $this->element['publish_down'];
		}
		
		// @todo Enforce ACL checks to determine if the field should be enabled or not
		
		// Get the HTML
		return JHTML::_('jgrid.published', $this->value, $this->rowid, $prefix, $enabled, $checkbox, $publish_up, $publish_down);
	}
}

<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

if(!class_exists('JFormFieldMedia')) {
	require_once JPATH_LIBRARIES.'/cms/form/field/media.php';
}

/**
 * Form Field class for the FOF framework
 * Media selection field.
 *
 * @since       2.0
 */
class FOFFormFieldMedia extends JFormFieldMedia implements FOFFormField
{
	protected $static;
	
	protected $repeatable;
	
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
	
	/**
	 * Get the rendering of this field type for static display, e.g. in a single
	 * item view (typically a "read" task).
	 * 
	 * @since 2.0
	 */
	public function getStatic()
	{
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$width = $this->element['width'] ? ' width="' . (string) $this->element['width'] . '"' : '';
		$height = $this->element['height'] ? ' height="' . (string) $this->element['height'] . '"' : '';
		$align = $this->element['align'] ? ' align="' . (string) $this->element['align'] . '"' : '';
		$rel = $this->element['rel'] ? ' rel="' . (string) $this->element['rel'] . '"' : '';
		$alt = $this->element['alt'] ? ' alt="' . JText::_((string) $this->element['alt']) . '"' : '';
		$title = $this->element['title'] ? ' title="' . JText::_((string) $this->element['title']) . '"' : '';
		
		return '<img id="' . $this->id . '" ' .
				'src="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' .
				$class . $width . $height . $align . $rel . $alt . $title . '/>';
	}

	/**
	 * Get the rendering of this field type for a repeatable (grid) display,
	 * e.g. in a view listing many item (typically a "browse" task)
	 * 
	 * @since 2.0
	 */
	public function getRepeatable()
	{
		return $this->getStatic();
	}	
}

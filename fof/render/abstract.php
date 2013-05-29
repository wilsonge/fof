<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Abstract view renderer class. The renderer is what turns XML view templates
 * into actual HTML code, renders the submenu links and potentially wraps the
 * HTML output in a div with a component-specific ID.
 *
 * @package  FrameworkOnFramework
 * @since    2.0
 */
abstract class FOFRenderAbstract
{
	/** @var int Priority of this renderer. Higher means more important */
	protected $priority = 50;

	/** @var int Is this renderer enabled? */
	protected $enabled = false;

	/**
	 * Returns the information about this renderer
	 *
	 * @return object
	 */
	public function getInformation()
	{
		return (object) array(
				'priority'	 => $this->priority,
				'enabled'	 => $this->enabled,
		);
	}

	/**
	 * Echoes any HTML to show before the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 * @param   FOFInput  $input   The input array (request parameters)
	 * @param   array     $config  The view configuration array
	 *
	 * @return  void
	 */
	abstract public function preRender($view, $task, $input, $config = array());

	/**
	 * Echoes any HTML to show after the view template
	 *
	 * @param   string    $view    The current view
	 * @param   string    $task    The current task
	 * @param   FOFInput  $input   The input array (request parameters)
	 * @param   array     $config  The view configuration array
	 *
	 * @return  void
	 */
	abstract public function postRender($view, $task, $input, $config = array());

	/**
	 * Renders a FOFForm and returns the corresponding HTML
	 *
	 * @param   FOFForm   &$form     The form to render
	 * @param   FOFModel  $model     The model providing our data
	 * @param   FOFInput  $input     The input object
	 * @param   string    $formType  The form type: edit, browse or read
	 *
	 * @return  string    The HTML rendering of the form
	 */
	public function renderForm(FOFForm &$form, FOFModel $model, FOFInput $input, $formType = null)
	{
		if (is_null($formType))
		{
			$formType = $form->getAttribute('type', 'edit');
		}
		else
		{
			$formType = strtolower($formType);
		}
		switch ($formType)
		{
			case 'browse':
				return $this->renderFormBrowse($form, $model, $input);
				break;

			case 'read':
				return $this->renderFormRead($form, $model, $input);
				break;

			default:
				return $this->renderFormEdit($form, $model, $input);
				break;
		}
	}

	/**
	 * Renders a FOFForm for a Browse view and returns the corresponding HTML
	 *
	 * @param   FOFForm   &$form  The form to render
	 * @param   FOFModel  $model  The model providing our data
	 * @param   FOFInput  $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormBrowse(FOFForm &$form, FOFModel $model, FOFInput $input);

	/**
	 * Renders a FOFForm for a Browse view and returns the corresponding HTML
	 *
	 * @param   FOFForm   &$form  The form to render
	 * @param   FOFModel  $model  The model providing our data
	 * @param   FOFInput  $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormRead(FOFForm &$form, FOFModel $model, FOFInput $input);

	/**
	 * Renders a FOFForm for a Browse view and returns the corresponding HTML
	 *
	 * @param   FOFForm   &$form  The form to render
	 * @param   FOFModel  $model  The model providing our data
	 * @param   FOFInput  $input  The input object
	 *
	 * @return  string    The HTML rendering of the form
	 */
	abstract protected function renderFormEdit(FOFForm &$form, FOFModel $model, FOFInput $input);

	/**
	 * Renders a raw FOFForm and returns the corresponding HTML
	 *
	 * @param   FOFForm   &$form  	The form to render
	 * @param   FOFModel  $model  	The model providing our data
	 * @param   FOFInput  $input  	The input object
	 * @param   string	  $formType The form type e.g. 'edit' or 'read'
	 *
	 * @return  string    The HTML rendering of the form
	 */
	protected function renderFormRaw(FOFForm &$form, FOFModel $model, FOFInput $input, $formType)
	{
		$html = '';

		foreach ($form->getFieldsets() as $fieldset)
		{
			$fields = $form->getFieldset($fieldset->name);

			if (isset($fieldset->class))
			{
				$class = 'class="' . $fieldset->class . '"';
			}
			else
			{
				$class = '';
			}

			$html .= "\t" . '<div id="' . $fieldset->name . '" ' . $class . '>' . PHP_EOL;

			if (isset($fieldset->label) && !empty($fieldset->label))
			{
				$html .= "\t\t" . '<h3>' . JText::_($fieldset->label) . '</h3>' . PHP_EOL;
			}

			foreach ($fields as $field)
			{
				$title		 = $field->title;
				$required	 = $field->required;
				$labelClass	 = $field->labelClass;
				$description = $field->description;

				if ($formType == 'read')
				{
					$input = $field->static;
				}
				else if ($formType == 'edit')
				{
					$input = $field->input;
				}

				if (empty($title))
				{
					$html .= "\t\t\t" . $input . PHP_EOL;

					if (!empty($description) && $formType == 'edit')
					{
						$html .= "\t\t\t\t" . '<span class="help-block">';
						$html .= JText::_($description) . '</span>' . PHP_EOL;
					}
				}
				else
				{
					$html .= "\t\t\t" . '<div class="control-group">' . PHP_EOL;
					$html .= "\t\t\t\t" . '<label class="control-label ' . $labelClass . '" for="' . $field->id . '">' . PHP_EOL;
					$html .= "\t\t\t\t" . JText::_($title) . PHP_EOL;

					if ($required)
					{
						$html .= ' *';
					}
					$html .= "\t\t\t\t" . '</label>' . PHP_EOL;
					$html .= "\t\t\t\t" . '<div class="controls">' . PHP_EOL;
					$html .= "\t\t\t\t" . $input . PHP_EOL;

					if (!empty($description))
					{
						$html .= "\t\t\t\t" . '<span class="help-block">';
						$html .= JText::_($description) . '</span>' . PHP_EOL;
					}

					$html .= "\t\t\t\t" . '</div>' . PHP_EOL;
					$html .= "\t\t\t" . '</div>' . PHP_EOL;
				}
			}

			$html .= "\t" . '</div>' . PHP_EOL;
		}

		return $html;
	}
}

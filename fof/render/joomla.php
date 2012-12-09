<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Default Joomla! 1.5, 1.7, 2.5 view renderer class
 */
class FOFRenderJoomla extends FOFRenderAbstract
{
	/**
	 * Public constructor. Determines the priority of this class and if it should be enabled
	 */
	public function __construct() {
		$this->priority = 50;
		$this->enabled = true;
	}

	/**
	 * Echoes any HTML to show before the view template
	 * 
	 * @param   string  $view   The current view
	 * @param   string  $task   The current task
	 * @param   array   $input  The input array (request parameters)
	 */
	public function preRender($view, $task, $input, $config=array())
	{
		$format = $input->getCmd('format', 'html');
		if(empty($format)) $format = 'html';
		if($format != 'html') return;
		
		// Wrap output in a Joomla-versioned div
		$version = new JVersion;
		$version = str_replace('.', '', $version->RELEASE);
		echo "<div class=\"joomla-version-$version\">\n";

		// Render submenu and toolbar
		$this->renderButtons($view, $task, $input, $config);
		$this->renderLinkbar($view, $task, $input, $config);
	}

	/**
	 * Echoes any HTML to show after the view template
	 * 
	 * @param   string  $view   The current view
	 * @param   string  $task   The current task
	 * @param   array   $input  The input array (request parameters)
	 */
	public function postRender($view, $task, $input, $config=array())
	{
		echo "</div>\n";
	}

	/**
	 * Renders a FOFForm for a Browse view and returns the corresponding HTML
	 * 
	 * @param   FOFForm   $form      The form to render
	 * @param   FOFModel  $model     The model providing our data
	 * @param   FOFInput  $input     The input object
	 * 
	 * @return  string    The HTML rendering of the form
	 */
	protected function renderFormBrowse(FOFForm &$form, FOFModel $model, FOFInput $input)
	{
		// @todo Get header fields
		
		// @todo Get filters
		
		// @todo Start the table output
		
		// @todo Render header if enabled
		
		// @todo Render filter row if enabled
		
		// @todo Loop through rows and fields, or show placeholder for no rows
		
		// @todo Render the pagination bar, if enabled
		
		// @todo End the table output
	}

	/**
	 * Renders a FOFForm for a Browse view and returns the corresponding HTML
	 * 
	 * @param   FOFForm   $form      The form to render
	 * @param   FOFModel  $model     The model providing our data
	 * @param   FOFInput  $input     The input object
	 * 
	 * @return  string    The HTML rendering of the form
	 */
	protected function renderFormRead(FOFForm &$form, FOFModel $model, FOFInput $input)
	{
		// Get the key for this model's table
		$key = $model->getTable()->getKeyName();
		$keyValue = $model->getId();
		
		$html = '';
		
		foreach($form->getFieldsets() as $fieldset) {
			$fields = $form->getFieldset($fieldset->name);

			if(isset($fieldset->class)) {
				$class = 'class="'.$fieldset->class.'"';
			} else {
				$class = '';
			}

			$html .= "\t".'<div id="'.$fieldset->name.'" '.$class.'>'.PHP_EOL;

			if(isset($fieldset->label) && !empty($fieldset->label)) {
				$html .= "\t\t".'<h3>'.JText::_($fieldset->label).'</h3>'.PHP_EOL;
			}

			foreach($fields as $field) {
				$label = $field->label;
				$static = $field->static;

				$html .= "<div class=\"fof-row\">";
				$html .= "\t\t\t".$label.PHP_EOL;
				$html .= "\t\t\t".$static.PHP_EOL;
				$html .= "</div>";
			}

			$html .= "\t".'</div>'.PHP_EOL;
		}
		
		return $html;
	}

	/**
	 * Renders a FOFForm for a Browse view and returns the corresponding HTML
	 * 
	 * @param   FOFForm   $form      The form to render
	 * @param   FOFModel  $model     The model providing our data
	 * @param   FOFInput  $input     The input object
	 * 
	 * @return  string    The HTML rendering of the form
	 */
	protected function renderFormEdit(FOFForm &$form, FOFModel $model, FOFInput $input)
	{
		// Get the key for this model's table
		$key = $model->getTable()->getKeyName();
		$keyValue = $model->getId();
		
		$html = '';
		
		$html .= '<form action="index.php" method="post" name="adminForm" id="adminForm">'.PHP_EOL;
		$html .= "\t".'<input type="hidden" name="option" value="'.$input->getCmd('option').'" />'.PHP_EOL;
		$html .= "\t".'<input type="hidden" name="view" value="'.$input->getCmd('view', 'edit').'" />'.PHP_EOL;
		$html .= "\t".'<input type="hidden" name="task" value="" />'.PHP_EOL;
		
		$html .= "\t".'<input type="hidden" name="'.$key.'" value="'.$keyValue.'" />'.PHP_EOL;
		$html .= "\t".'<input type="hidden" name="'.JFactory::getSession()->getFormToken().'" value="1" />'.PHP_EOL;

		foreach($form->getFieldsets() as $fieldset) {
			$fields = $form->getFieldset($fieldset->name);

			if(isset($fieldset->class)) {
				$class = 'class="'.$fieldset->class.'"';
			} else {
				$class = '';
			}

			$element = empty($fields) ? 'div' : 'fieldset';
			$html .= "\t".'<'.$element.' id="'.$fieldset->name.'" '.$class.'>'.PHP_EOL;

			if(isset($fieldset->label) && !empty($fieldset->label)) {
				$html .= "\t\t".'<legend>'.JText::_($fieldset->label).'</legend>'.PHP_EOL;
			}

			foreach($fields as $field) {
				$label = $field->label;
				$input = $field->input;

				$html .= "\t\t\t".$label.PHP_EOL;
				$html .= "\t\t\t".$input.PHP_EOL;
			}

			$element = empty($fields) ? 'div' : 'fieldset';
			$html .= "\t".'</'.$element.'>'.PHP_EOL;
		}

		$html .= '</form>';
		
		return $html;
	}

	/**
	 * Renders the submenu (link bar)
	 * 
	 * @param   string    $view    The active view name
	 * @param   string    $task    The current task
	 * @param   FOFInput  $input   The input object
	 * @param   array     $config  Extra configuration variables for the toolbar
	 */
	protected function renderLinkbar($view, $task, $input, $config=array())
	{
		// Do not render a submenu unless we are in the the admin area
		$toolbar = FOFToolbar::getAnInstance($input->getCmd('option','com_foobar'), $config);
		$renderFrontendSubmenu = $toolbar->getRenderFrontendSubmenu();

		list($isCli, $isAdmin) = FOFDispatcher::isCliAdmin();
		if(!$isAdmin && !$renderFrontendSubmenu) return;

		$links = $toolbar->getLinks();
		if(!empty($links)) {
			foreach($links as $link) {
				JSubMenuHelper::addEntry($link['name'], $link['link'], $link['active']);
			}
		}
	}

	/**
	 * Renders the toolbar buttons
	 * 
	 * @param   string    $view    The active view name
	 * @param   string    $task    The current task
	 * @param   FOFInput  $input   The input object
	 * @param   array     $config  Extra configuration variables for the toolbar
	 */
	protected function renderButtons($view, $task, $input, $config=array())
	{
		// Do not render buttons unless we are in the the frontend area and we are asked to do so
		$toolbar = FOFToolbar::getAnInstance($input->getCmd('option','com_foobar'), $config);
		$renderFrontendButtons = $toolbar->getRenderFrontendButtons();

		list($isCli, $isAdmin) = FOFDispatcher::isCliAdmin();
		if($isAdmin || !$renderFrontendButtons) return;

		// Load main backend language, in order to display toolbar strings
		// (JTOOLBAR_BACK, JTOOLBAR_PUBLISH etc etc)
		$jlang = JFactory::getLanguage();
		$jlang->load('joomla', JPATH_ADMINISTRATOR, null, true);

		$title = JFactory::getApplication()->get('JComponentTitle');
		$bar = JToolBar::getInstance('toolbar');

		// delete faux links, since if SEF is on, Joomla will follow the link instead of submitting the form
		$bar_content = str_replace('href="#"','', $bar->render());

		echo '<div id="FOFHeaderHolder">' , $bar_content , $title , '<div style="clear:both"></div>', '</div>';
	}
}
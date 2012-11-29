<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Akeeba Strapper view renderer class.
 */
class FOFRenderStrapper extends FOFRenderAbstract
{
	/**
	 * Public constructor. Determines the priority of this class and if it should be enabled
	 */
	public function __construct() {
		$this->priority = 60;
		$this->enabled = class_exists('AkeebaStrapper');
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

		// Wrap output in an akeeba-bootstrap class div
		echo "<div class=\"akeeba-bootstrap\">\n";
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
		$format = $input->getCmd('format', 'html');
		if($format != 'html') return;
		
		echo "</div>\n";
		echo "</div>\n";
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
			echo "<ul class=\"nav nav-tabs\">\n";
			foreach($links as $link) {
				$dropdown = false;
				if(array_key_exists('dropdown', $link)) {
					$dropdown = $link['dropdown'];
				}
				
				if($dropdown) {
					echo "<li";
					$class = 'dropdown';
					if($link['active']) $class .= ' active';
					echo ' class="'.$class.'">';
					
					echo '<a class="dropdown-toggle" data-toggle="dropdown" href="#">';
					if($link['icon']) {
						echo "<i class=\"icon icon-".$link['icon']."\"></i>";
					}
					echo $link['name'];
					echo '<b class="caret"></b>';
					echo '</a>';
					
					echo "\n<ul class=\"dropdown-menu\">";
					foreach($link['items'] as $item) {
						
						echo "<li";
						if($item['active']) echo ' class="active"';
						echo ">";
						if($item['icon']) {
							echo "<i class=\"icon icon-".$item['icon']."\"></i>";
						}
						if($item['link']) {
							echo "<a tabindex=\"-1\" href=\"".$item['link']."\">".$item['name']."</a>";
						} else {
							echo $item['name'];
						}
						echo "</li>";
						
					}
					echo "</ul>\n";
					
				} else {
					echo "<li";
					if($link['active']) echo ' class="active"';
					echo ">";
					if($link['icon']) {
						echo "<i class=\"icon icon-".$link['icon']."\"></i>";
					}
					if($link['link']) {
						echo "<a href=\"".$link['link']."\">".$link['name']."</a>";
					} else {
						echo $link['name'];
					}					
				}
				
				echo "</li>\n";
			}
			echo "</ul>\n";
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
		
		$bar = JToolBar::getInstance('toolbar');
		$items = $bar->getItems();
		
		$substitutions = array(
			'icon-32-new'		=>  'icon-plus',
			'icon-32-edit'		=>  'icon-pencil',
			'icon-32-publish'	=>  'icon-eye-open',
			'icon-32-unpublish'	=>  'icon-eye-close',
			'icon-32-delete'	=>  'icon-trash',
			'icon-32-edit'		=>  'icon-edit',
			'icon-32-copy'		=>  'icon-th-large',
			'icon-32-cancel'	=>  'icon-remove',
			'icon-32-back'		=>  'icon-circle-arrow-left',
			'icon-32-apply'		=>  'icon-ok',
			'icon-32-save'		=>  'icon-hdd',
			'icon-32-save-new'	=>  'icon-repeat',
		);
		
		$html = array();
		$html[] = '<div class="well" id="' . $bar->getName() . '">';
		foreach($items as $node) {
			$type = $node[0];
			$button = $bar->loadButtonType($type);
			if ($button !== false) {
				if(method_exists($button, 'fetchId')) {
					$id = call_user_func_array(array(&$button, 'fetchId'), $node);
				} else {
					$id = null;
				}
				$action = call_user_func_array(array(&$button, 'fetchButton'), $node);
				$action = str_replace('class="toolbar"', 'class="toolbar btn"', $action);
				$action = str_replace('<span ', '<i ', $action);
				$action = str_replace('</span>', '</i>', $action);
				$action = str_replace(array_keys($substitutions), array_values($substitutions), $action);
				$html[] = $action;
			}
		}
		$html[] = '</div>';
		
		echo implode("\n", $html);
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
		
		$html .= '<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">'.PHP_EOL;
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

			$html .= "\t".'<div id="'.$fieldset->name.'" '.$class.'>'.PHP_EOL;

			if(isset($fieldset->label) && !empty($fieldset->label)) {
				$html .= "\t\t".'<h3>'.JText::_($fieldset->label).'</h3>'.PHP_EOL;
			}

			foreach($fields as $field) {
				$title = $field->title;
				$required = $field->required;
				$labelClass = $field->labelClass;
				$description = $field->description;
				
				$input = $field->input;

				$html .= "\t\t\t".'<div class="control-group">'.PHP_EOL;
				$html .= "\t\t\t\t".'<label class="control-label '.$labelClass.'" for="'.$field->id.'">'.PHP_EOL;
				$html .= "\t\t\t\t".JText::_($title).PHP_EOL;
				if($required) {
					$html .= ' *';
				}
				$html .= "\t\t\t\t".'</label>'.PHP_EOL;
				$html .= "\t\t\t\t".'<div class="controls">'.PHP_EOL;
				$html .= "\t\t\t\t".$input.PHP_EOL;
				if(!empty($description)) {
					$html .= "\t\t\t\t".'<span class="help-block">';
					$html .= JText::_($description).'</span>'.PHP_EOL;
				}
				$html .= "\t\t\t\t".'</div>'.PHP_EOL;
				$html .= "\t\t\t".'</div>'.PHP_EOL;
			}

			$html .= "\t".'</div>'.PHP_EOL;
		}

		$html .= '</form>';
		
		return $html;
	}
}
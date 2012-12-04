<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 3 or later; see LICENSE.txt
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

class FOFForm extends JForm
{
	/**
	 * Method to get an instance of a form.
	 *
	 * @param   string  $name     The name of the form.
	 * @param   string  $data     The name of an XML file or string to load as the form definition.
	 * @param   array   $options  An array of form options.
	 * @param   string  $replace  Flag to toggle whether form fields should be replaced if a field
	 *                            already exists with the same group/name.
	 * @param   string  $xpath    An optional xpath to search for the fields.
	 *
	 * @return  object  FOFForm instance.
	 *
	 * @since   2.0
	 * @throws  InvalidArgumentException if no data provided.
	 * @throws  RuntimeException if the form could not be loaded.
	 */
	public static function getInstance($name, $data = null, $options = array(), $replace = true, $xpath = false)
	{
		// Reference to array with form instances
		$forms = &self::$forms;

		// Only instantiate the form if it does not already exist.
		if (!isset($forms[$name]))
		{
			$data = trim($data);

			if (empty($data))
			{
				throw new InvalidArgumentException(sprintf('FOFForm::getInstance(name, *%s*)', gettype($data)));
			}

			// Instantiate the form.
			$forms[$name] = new FOFForm($name, $options);

			// Load the data.
			if (substr(trim($data), 0, 1) == '<')
			{
				if ($forms[$name]->load($data, $replace, $xpath) == false)
				{
					throw new RuntimeException('FOFForm::getInstance could not load form');
				}
			}
			else
			{
				if ($forms[$name]->loadFile($data, $replace, $xpath) == false)
				{
					throw new RuntimeException('FOFForm::getInstance could not load file');
				}
			}
		}

		return $forms[$name];
	}
	
	/**
	 * Returns the value of an attribute of the form itself
	 * 
	 * @param   string  $attribute  The name of the attribute
	 * @param   mixed   $default    Optional default value to return
	 * 
	 * @return  mixed
	 * 
	 * @since 2.0
	 */
	public function getAttribute($attribute, $default = null)
	{
		$value = $this->xml->attributes()->$attribute;
		if(is_null($value)) {
			return $default;
		} else {
			return (string)$value;
		}
	}
	
	/**
	 * Loads the CSS files defined in the form, based on its cssfiles attribute
	 * 
	 * @since 2.0
	 */
	public function loadCSSFiles()
	{
		$cssfiles = $this->getAttribute('cssfiles');
		
		if(empty($cssfiles)) {
			return;
		}
		
		$cssfiles = explode(',', $cssfiles);
		foreach($cssfiles as $cssfile) {
			FOFTemplateUtils::addCSS(trim($cssfile));
		}
	}
	
	/**
	 * Loads the Javascript files defined in the form, based on its jsfiles attribute
	 * 
	 * @since 2.0
	 */
	public function loadJSFiles()
	{
		$jsfiles = $this->getAttribute('jsfiles');
		
		if(empty($jsfiles)) {
			return;
		}
		
		$jsfiles = explode(',', $jsfiles);
		foreach($jsfiles as $jsfile) {
			FOFTemplateUtils::addJS(trim($jsfile));
		}
	}
	
	/**
	 * Returns a reference to the protected $data object, allowing direct
	 * access to and manipulation of the form's data.
	 * 
	 * @return   JRegistry  The form's data registry
	 * 
	 * @since 2.0
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Proxy for {@link FOFFormHelper::loadFieldType()}.
	 *
	 * @param   string   $type  The field type.
	 * @param   boolean  $new   Flag to toggle whether we should get a new instance of the object.
	 *
	 * @return  mixed  FOFFormField object on success, false otherwise.
	 *
	 * @since   2.0
	 */
	protected function loadFieldType($type, $new = true)
	{
		return FOFFormHelper::loadFieldType($type, $new);
	}
	
	/**
	 * Proxy for {@link FOFFormHelper::loadRuleType()}.
	 *
	 * @param   string   $type  The rule type.
	 * @param   boolean  $new   Flag to toggle whether we should get a new instance of the object.
	 *
	 * @return  mixed  JFormRule object on success, false otherwise.
	 *
	 * @see     FOFFormHelper::loadRuleType()
	 * @since   2.0
	 */
	protected function loadRuleType($type, $new = true)
	{
		return FOFFormHelper::loadRuleType($type, $new);
	}
	
	/**
	 * Proxy for {@link FOFFormHelper::addFieldPath()}.
	 *
	 * @param   mixed  $new  A path or array of paths to add.
	 *
	 * @return  array  The list of paths that have been added.
	 *
	 * @since   2.0
	 */
	public static function addFieldPath($new = null)
	{
		return FOFFormHelper::addFieldPath($new);
	}

	/**
	 * Proxy for FOFFormHelper::addFormPath().
	 *
	 * @param   mixed  $new  A path or array of paths to add.
	 *
	 * @return  array  The list of paths that have been added.
	 *
	 * @see     FOFFormHelper::addFormPath()
	 * @since   2.0
	 */
	public static function addFormPath($new = null)
	{
		return FOFFormHelper::addFormPath($new);
	}

	/**
	 * Proxy for FOFFormHelper::addRulePath().
	 *
	 * @param   mixed  $new  A path or array of paths to add.
	 *
	 * @return  array  The list of paths that have been added.
	 *
	 * @see FOFFormHelper::addRulePath()
	 * @since   2.0
	 */
	public static function addRulePath($new = null)
	{
		return FOFFormHelper::addRulePath($new);
	}
}
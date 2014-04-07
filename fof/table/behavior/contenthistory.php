<?php
/**
 * @package     FrameworkOnFramework
 * @subpackage  table
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('F0F_INCLUDED') or die;

/**
 * FrameworkOnFramework table behavior class for content History
 *
 * @package  FrameworkOnFramework
 * @since    2.2.0
 */
class F0FTableBehaviorContenthistory extends F0FTableBehavior
{
	/**
	 * The event which runs after storing (saving) data to the database
	 *
	 * @param   F0FTable  &$table  The table which calls this event
	 *
	 * @return  boolean  True to allow saving without an error
	 */
	public function onAfterStore(&$table)
	{
		$aliasParts = explode('.', $table->getContentType());
		$this->checkContentType($table);

		if (JComponentHelper::getParams($aliasParts[0])->get('save_history', 0))
		{
			$historyHelper = new JHelperContenthistory($table->getContentType());
			$historyHelper->store($table);
		}

		return true;
	}

	/**
	 * The event which runs before deleting a record
	 *
	 * @param   F0FTable &$table  The table which calls this event
	 * @param   integer  $oid  The PK value of the record to delete
	 *
	 * @return  boolean  True to allow the deletion
	 */
	public function onBeforeDelete(&$table, $oid)
	{
		$aliasParts = explode('.', $table->getContentType());

		if (JComponentHelper::getParams($aliasParts[0])->get('save_history', 0))
		{
			$historyHelper = new JHelperContenthistory($table->getContentType());
			$historyHelper->deleteHistory($table);
		}

		return true;
	}

	/**
	 * Check if a UCM content type exists for this resource, and
	 * create it if it does not
	 *
	 * @param   F0FTable   &$table  	The table which calls this event
	 *
	 */
	protected function checkContentType(&$table)
	{
		$contentType = new JTableContenttype($table->getDbo());

		$alias = $table->getContentType();

		$aliasParts = explode('.', $table->getContentType());
		$input = new F0FInput;
		$options = array(
			'component' 	=> $aliasParts[0],
			'view'		=> $aliasParts[1],
			'table_prefix'	=> ucfirst(F0FInflector::pluralize(substr($aliasParts[0], strpos($aliasParts[0], "_")  + 1)) . 'Table')
		);

		// Fetch the extension name
		$component = $options['component'];
		$component = JComponentHelper::getComponent($component);

		// Fetch the name using the menu item
		$query = $table->getDbo()->getQuery(true);
		$query->select('title')->from('#__menu')->where('component_id = ' . (int) $component->id);
		$table->getDbo()->setQuery($query);
		$component_name = JText::_($table->getDbo()->loadResult());

		$name = $component_name . ' ' . ucfirst($options['view']);

		// Create a new content type for our resource
		if (!$contentType->load(array('type_alias' => $alias)))
		{
			$contentType->type_title = $name;
			$contentType->type_alias = $alias;
			$contentType->table = json_encode(
				array(
					'special' => array(
						'dbtable' => $table->getTableName(),
						'key'     => $table->getKeyName(),
						'type'    => $name,
						'prefix'  => $options['table_prefix'],
						'class'   => 'F0FTable',
						'config'  => 'array()'
					),
					'common' => array(
						'dbtable' => '#__ucm_content',
						'key' => 'ucm_id',
						'type' => 'CoreContent',
						'prefix' => 'JTable',
						'config' => 'array()'
					)
				)
			);

			$contentType->field_mappings = json_encode(
				array(
					'common' => array(
						0 => array(
							"core_content_item_id" => $table->getKeyName(),
							"core_title"           => $this->getUcmCoreAlias($table, 'title'),
							"core_state"           => $this->getUcmCoreAlias($table, 'enabled'),
							"core_alias"           => $this->getUcmCoreAlias($table, 'alias'),
							"core_created_time"    => $this->getUcmCoreAlias($table, 'created_on'),
							"core_modified_time"   => $this->getUcmCoreAlias($table, 'created_by'),
							"core_body"            => $this->getUcmCoreAlias($table, 'body'),
							"core_hits"            => $this->getUcmCoreAlias($table, 'hits'),
							"core_publish_up"      => $this->getUcmCoreAlias($table, 'publish_up'),
							"core_publish_down"    => $this->getUcmCoreAlias($table, 'publish_down'),
							"core_access"          => $this->getUcmCoreAlias($table, 'access'),
							"core_params"          => $this->getUcmCoreAlias($table, 'params'),
							"core_featured"        => $this->getUcmCoreAlias($table, 'featured'),
							"core_metadata"        => $this->getUcmCoreAlias($table, 'metadata'),
							"core_language"        => $this->getUcmCoreAlias($table, 'language'),
							"core_images"          => $this->getUcmCoreAlias($table, 'images'),
							"core_urls"            => $this->getUcmCoreAlias($table, 'urls'),
							"core_version"         => $this->getUcmCoreAlias($table, 'version'),
							"core_ordering"        => $this->getUcmCoreAlias($table, 'ordering'),
							"core_metakey"         => $this->getUcmCoreAlias($table, 'metakey'),
							"core_metadesc"        => $this->getUcmCoreAlias($table, 'metadesc'),
							"core_catid"           => $this->getUcmCoreAlias($table, 'cat_id'),
							"core_xreference"      => $this->getUcmCoreAlias($table, 'xreference'),
							"asset_id"             => $this->getUcmCoreAlias($table, 'asset_id')
						)
					),
					'special' => array(
						0 => array(
						)
					)
				)
			);

			$contentType->router = '';

			$contentType->store();
		}
	}

	/**
	 * Utility methods that fetches the column name for the field.
	 * If it does not exists, returns a "null" string
	 *
	 * @param   F0FTable   $table  	The table which calls this event
	 * @param   string     $alias   The alias of the content type
	 *
	 * @return string The column name
	 */
	protected function getUcmCoreAlias($table, $alias)
	{
		$alias = $table->getColumnAlias($alias);

		if (in_array($alias, $table->getKnownFields()))
		{
			return $alias;
		}

		return "null";
	}
}

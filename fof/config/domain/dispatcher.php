<?php
/**
 *  @package     FrameworkOnFramework
 *  @subpackage  config
 *  @copyright   Copyright (c)2010-2012 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2, or later
 */

defined('FOF_INCLUDED') or die();

class FOFConfigDomainDispatcher implements FOFConfigDomainInterface
{
	public function parseDomain(SimpleXMLElement $xml, array &$ret)
	{
		// Initialise
		$ret['dispatcher'] = array();

		// Parse the dispatcher configuration
		$dispatcherData = $xml->dispatcher;

		// Sanity check
		if (empty($dispatcherData))
		{
			return;
		}

		$options = $xml->xpath('dispatcher/option');
		if (!empty($options))
		{
			foreach ($options as $option)
			{
				$key = (string)$option['name'];
				$ret['dispatcher'][$key] = (string)$option;
			}
		}
	}

	public function get(&$configuration, $var, $default)
	{
		if(isset($configuration['dispatcher'][$var]))
		{
			return $configuration['dispatcher'][$var];
		}
		else
		{
			return $default;
		}
	}
}
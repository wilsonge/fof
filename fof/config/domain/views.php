<?php
/**
 *  @package     FrameworkOnFramework
 *  @subpackage  config
 *  @copyright   Copyright (c)2010-2012 Nicholas K. Dionysopoulos
 *  @license     GNU General Public License version 2, or later
 */

defined('FOF_INCLUDED') or die();

class FOFConfigDomainViews implements FOFConfigDomainInterface
{
	public function parseDomain(SimpleXMLElement $xml, array &$ret)
	{
		// Initialise
		$ret['views'] = array();

		// Parse the dispatcher configuration
		$viewData = $xml->xpath('view');

		// Sanity check
		if (empty($viewData))
		{
			return;
		}

		foreach($viewData as $aView)
		{
			$key = (string)$aView['name'];

			// Parse ACL options
			$ret['views'][$key]['acl'] = array();
			$aclData = $aView->xpath('acl/task');
			if (!empty($aclData))
			{
				foreach($aclData as $acl)
				{
					$k = (string)$acl['name'];
					$ret['views'][$key]['acl'][$k] = (string)$acl;
				}
			}

			// Parse taskmap
			$ret['views'][$key]['taskmap'] = array();
			$taskmapData = $aView->xpath('taskmap/task');
			if (!empty($taskmapData))
			{
				foreach($taskmapData as $map)
				{
					$k = (string)$map['name'];
					$ret['views'][$key]['taskmap'][$k] = (string)$map;
				}
			}
		}
	}

	public function get(&$configuration, $var, $default)
	{
		$parts = explode('.', $var);

		$view = $parts[0];
		$method = 'get' . ucfirst($parts[1]);

		if (!method_exists($this, $method))
		{
			return $default;
		}

		array_shift($parts);
		array_shift($parts);

		$ret = $this->$method($view, $configuration, $parts, $default);

		return $ret;
	}

	protected function getTaskmap($view, &$configuration, $params, $default = array())
	{
		$taskmap = array();

		if (isset($configuration['views']['*']) && isset($configuration['views']['*']['taskmap']))
		{
			$taskmap = $configuration['views']['*']['taskmap'];
		}

		if (isset($configuration['views'][$view]) && isset($configuration['views'][$view]['taskmap']))
		{
			$taskmap = array_merge($taskmap, $configuration['views'][$view]['taskmap']);
		}

		if (empty($taskmap))
		{
			return $default;
		}

		return $taskmap;
	}

	protected function getAcl($view, &$configuration, $params, $default = '')
	{
		$aclmap = array();

		if (isset($configuration['views']['*']) && isset($configuration['views']['*']['acl']))
		{
			$aclmap = $configuration['views']['*']['acl'];
		}

		if (isset($configuration['views'][$view]) && isset($configuration['views'][$view]['acl']))
		{
			$aclmap = array_merge($aclmap, $configuration['views'][$view]['acl']);
		}

		$acl = $default;

		if (isset($aclmap['*']))
		{
			$acl = $aclmap['*'];
		}

		if (isset($aclmap[$params[0]]))
		{
			$acl = $aclmap[$params[0]];
		}

		return $acl;
	}
}
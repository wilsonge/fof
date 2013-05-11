<?php
/**
 * @package    FrameworkOnFramework
 * @subpackage hal
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class FOFHalRenderJson implements FOFHalRenderInterface
{
	/**
	 * When data is an array we'll output the list of data under this key
	 *
	 * @var   string
	 */
	private $_dataKey = '_list';


	/**
	 * The document to render
	 *
	 * @var   FOFHalDocument
	 */
	protected $_document;

	public function __construct(&$document)
	{
		$this->_document = $document;
	}

	public function render($options = array())
	{
		if (isset($options['data_key']))
		{
			$this->_dataKey = $options['data_key'];
		}

		if (isset($options['json_options']))
		{
			$jsonOptions = $options['json_options'];
		}
		else
		{
			$jsonOptions = 0;
		}

		$serialiseThis = new stdClass();

		// Add links
		$collection = $this->_document->getLinks();
		$serialiseThis->_links = array();
		foreach ($collection as $rel => $links)
		{
			if (!is_array($links))
			{
				$serialiseThis->_links->$rel = $this->_getLink($links);
			}
			else
			{
				$serialiseThis->_links->$rel = array();
				foreach ($links as $link)
				{
					$serialiseThis->_links->$rel[] = $this->_getLink($link);
				}
			}
		}

		// Add embedded documents
		$collection = $this->_document->getEmbedded();
		if (!empty($collection))
		{
			$serialiseThis->_embedded->$rel = array();
			foreach ($collection as $rel => $embeddeddocs)
			{
				if (!is_array($embeddeddocs))
				{
					$embeddeddocs = array($embeddeddocs);
				}

				foreach ($embeddeddocs as $embedded)
				{
					$renderer = new FOFHalRenderJson($embedded);
					$serialiseThis->_embedded->$rel[] = $renderer->render($options);
				}
			}
		}

		// Add data
		if (is_object($data))
		{
			$data = (array)$data;
			if (!empty($data))
			{
				foreach($data as $k => $v)
				{
					$serialiseThis->$k = $v;
				}
			}
		}
		elseif (is_array($data))
		{
			$serialiseThis->{$this->_dataKey} = $data;
		}

		return json_encode($serialiseThis, $jsonOptions);
	}

	protected function _getLink(FOFHalLink $link)
	{
		$ret = array(
			'href'	=> $link->href
		);

		if ($link->templated)
		{
			$ret['templated'] = 'true';
		}

		if (!empty($link->name))
		{
			$ret['name'] = $link->name;
		}

		if (!empty($link->hreflang))
		{
			$ret['hreflang'] = $link->hreflang;
		}

		if (!empty($link->title))
		{
			$ret['title'] = $link->title;
		}

		return (object)$ret;
	}
}
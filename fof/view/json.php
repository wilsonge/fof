<?php
/**
 * @package    FrameworkOnFramework
 * @copyright  Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// Protect from unauthorized access
defined('_JEXEC') or die();

JLoader::import('joomla.application.component.view');

/**
 * FrameworkOnFramework JSON View class
 *
 * FrameworkOnFramework is a set of classes whcih extend Joomla! 1.5 and later's
 * MVC framework with features making maintaining complex software much easier,
 * without tedious repetitive copying of the same code over and over again.
 */
class FOFViewJson extends FOFViewHtml
{
	/**
	 * When set to true we'll add hypermedia to the output, implementing the
	 * HAL specification (http://stateless.co/hal_specification.html)
	 *
	 * @var   boolean
	 */
	public $useHypermedia = false;

	public function __construct($config = array())
	{
		parent::__construct($config);

		if (isset($config['use_hypermedia']))
		{
			$this->useHypermedia = (bool)$config['use_hypermedia'];
		}
	}

	protected function onDisplay($tpl = null)
	{
		// Load the model
		$model = $this->getModel();

		$items = $model->getItemList();
		$this->assignRef('items', $items);

		$document = JFactory::getDocument();
		if ($this->useHypermedia)
		{
			$document->setMimeEncoding('application/hal+json');
		}
		else
		{
			$document->setMimeEncoding('application/json');
		}

		if (is_null($tpl))
		{
			$tpl = 'json';
		}

		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JError::setErrorHandling(E_ALL, 'ignore');
		}
		$hasFailed = false;
		try
		{
			$result = $this->loadTemplate($tpl, true);
			if ($result instanceof Exception)
			{
				$hasFailed = true;
			}
		}
		catch (Exception $e)
		{
			$hasFailed = true;
		}

		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			if ($result instanceof Exception)
			{
				$hasFailed = true;
			}
			JError::setErrorHandling(E_WARNING, 'callback');
		}

		if ($hasFailed)
		{
			// Default JSON behaviour in case the template isn't there!
			if ($this->useHypermedia)
			{
				$haldocument = $this->_createDocumentWithHypermedia($items, $model);
				$json = $haldocument->render('json');
			}
			else
			{
				$json = json_encode($items);
			}

			// JSONP support
			$callback = $this->input->getVar('callback', null);
			if (!empty($callback))
			{
				echo $callback . '(' . $json . ')';
			}
			else
			{
				$defaultName = $this->input->getCmd('view', 'joomla');
				$filename = $this->input->getCmd('basename', $defaultName);

				$document->setName($filename);
				echo $json;
			}

			return false;
		}
		else
		{
			echo $result;
			return false;
		}
	}

	protected function onRead($tpl = null)
	{
		$model = $this->getModel();

		$item = $model->getItem();
		$this->assign('item', $item);

		$document = JFactory::getDocument();
		if ($this->useHypermedia)
		{
			$document->setMimeEncoding('application/hal+json');
		}
		else
		{
			$document->setMimeEncoding('application/json');
		}

		if (is_null($tpl))
		{
			$tpl = 'json';
		}

		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			JError::setErrorHandling(E_ALL, 'ignore');
		}

		$hasFailed = false;
		try
		{
			$result = $this->loadTemplate($tpl, true);
		}
		catch (Exception $e)
		{
			$hasFailed = true;
		}

		if (version_compare(JVERSION, '3.0', 'lt'))
		{
			if ($result instanceof Exception)
			{
				$hasFailed = true;
			}
			JError::setErrorHandling(E_WARNING, 'callback');
		}

		if ($hasFailed)
		{
			// Default JSON behaviour in case the template isn't there!
			if ($this->useHypermedia)
			{
				$haldocument = $this->_createDocumentWithHypermedia($item, $model);
				$json = $haldocument->render('json');
			}
			else
			{
				$json = json_encode($item);
			}

			// JSONP support
			$callback = $this->input->get('callback', null);
			if (!empty($callback))
			{
				echo $callback . '(' . $json . ')';
			}
			else
			{
				$defaultName = $this->input->getCmd('view', 'joomla');
				$filename = $this->input->getCmd('basename', $defaultName);
				$document->setName($filename);
				echo $json;
			}

			return false;
		}
		else
		{
			echo $result;
			return false;
		}
	}

	protected function _createDocumentWithHypermedia($data, $model = null)
	{
		// Create a new HAL document
		if (is_array($data))
		{
			$count = count($data);
		}
		else
		{
			$count = null;
		}

		if ($count == 1)
		{
			reset($data);
			$document = new FOFHalDocument(end($data));
		}
		else
		{
			$document = new FOFHalDocument($data);
		}


		// Create a self link
		$uri = (string)(JUri::getInstance());
		$uri = $this->_removeURIBase($uri);
		$uri = JRoute::_($uri);
		$document->addLink('self', new FOFHalLink($uri));

		// Create relative links in a record list context
		if (is_array($data) && ($model instanceof FOFModel))
		{
			$pagination = $model->getPagination();

			if ($pagination->get('pages.total') > 1)
			{
				// Try to guess URL parameters and create a prototype URL
				// NOTE: You are better off specialising this method
				$protoUri = $this->_getPrototypeURIForPagination();

				// The "first" link
				$uri = clone $protoUri;
				$uri->setVar('limitstart', 0);
				$uri = JRoute::_((string)$uri);

				$document->addLink('first', new FOFHalLink($uri));

				// Do we need a "prev" link?
				if ($pagination->get('pages.current') > 1)
				{
					$prevPage = $pagination->get('pages.current') - 1;
					$limitstart = ($prevPage - 1) * $pagination->limit;
					$uri = clone $protoUri;
					$uri->setVar('limitstart', $limitstart);
					$uri = JRoute::_((string)$uri);

					$document->addLink('prev', new FOFHalLink($uri));
				}

				// Do we need a "next" link?
				if ($pagination->get('pages.current') < $pagination->get('pages.total'))
				{
					$nextPage = $pagination->get('pages.current') + 1;
					$limitstart = ($nextPage - 1) * $pagination->limit;
					$uri = clone $protoUri;
					$uri->setVar('limitstart', $limitstart);
					$uri = JRoute::_((string)$uri);

					$document->addLink('next', new FOFHalLink($uri));
				}

				// The "last" link?
				$lastPage = $pagination->get('pages.total');
				$limitstart = ($lastPage - 1) * $pagination->limit;
				$uri = clone $protoUri;
				$uri->setVar('limitstart', $limitstart);
				$uri = JRoute::_((string)$uri);

				$document->addLink('last', new FOFHalLink($uri));
			}
		}

		return $document;
	}

	protected function _removeURIBase($uri)
	{
		static $root = null, $rootlen = 0;

		if (is_null($root))
		{
			$root = rtrim(JURI::base(),'/');
			$rootlen = strlen($root);
		}

		if (substr($uri, 0, $rootlen) == $root)
		{
			$uri = substr($uri, $rootlen);
		}

		return ltrim($uri, '/');
	}

	protected function _getPrototypeURIForPagination()
	{
		$protoUri = new JUri('index.php');
		$protoUri->setQuery($this->input->getData());
		$protoUri->delVar('savestate');
		$protoUri->delVar('base_path');

		return $protoUri;
	}
}
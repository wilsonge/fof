<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * @group 	View
 */
class FOFViewJsonTest extends FtestCase
{
	protected $view = null;

	public function setUp()
	{
		parent::setUp();

		// Force a JDocumentHTML instance
		$this->saveFactoryState();
		JFactory::$document = JDocument::getInstance('json');

		// Fake the server variables to get JURI working right
		global $_SERVER;
		$this->_stashedServer = $_SERVER;
		$_SERVER['HTTP_HOST'] = 'www.example.com';
		$_SERVER['REQUEST_URI'] = '/index.php?option=com_foobar';
		$_SERVER['SCRIPT_NAME'] = '/index.php';

		// Fake the session
		JFactory::$session = $this->getMockSession();
		$application = JFactory::getApplication('site');

		// Fake the template
		$template = (object)array(
			'template'		=> 'system',
		);
		$attribute = new ReflectionProperty($application, 'template');
		$attribute->setAccessible(TRUE);
		$attribute->setValue($application, $template);

		$_SERVER['REQUEST_METHOD'] = 'GET';

		// Replace the FOFPlatform with our fake one
		$this->saveFOFPlatform();
		$this->replaceFOFPlatform();

		$this->view = $this->getView();
	}

	protected function tearDown()
	{
		// Restore the JFactory
		$this->restoreFactoryState();

		// Restore the FOFPlatform object instance
		$this->restoreFOFPlatform();

		// Restore the $_SERVER global
		global $_SERVER;
		$_SERVER = $this->_stashedServer;

		// Call the parent
		parent::tearDown();
	}

	protected function getView()
	{
		$config = array();		
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'Ftest'));

		$view = new FOFViewRaw($config);

		return $view;
	}

	/**
	 * @cover  	FOFViewRaw::__construct()
	 */
	public function testContructorWithHyperMedia()
	{
		$config = array();				
		$config['use_hypermedia'] = true;
		$view = new FOFViewJson($config);
	}

	public function getViewsAndTasks()
	{
		$data = array();
		$data[] = array('foo', 'read', true);
		$data[] = array('foo', 'browse', false);
		$data[] = array('foo', 'read', false);
		$data[] = array('foo', 'browse', true);

		return $data;
	}

	/**
	 * @cover  			FOFViewRaw::onDisplay()
	 * @dataProvider	getViewsAndTasks
	 */
	public function testDisplayWithTasksAndViews($view, $task, $use_hypermedia)
	{
		$model = new FtestModel(array('table' => 'Ftest', 'option' => ''));
		$model->setState('task', $task);
		
		$config = array();		
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $view));
		$config['use_hypermedia'] = $use_hypermedia;

		$view = new FOFViewJson($config);

		$view->setModel($model, true);

		$this->expectOutputRegex('/[{\[][}\]]/');

		$view->display();
	}
}
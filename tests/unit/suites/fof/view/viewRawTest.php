<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_TESTS . '/unit/core/table/tableraw.php';
require_once JPATH_TESTS . '/unit/core/model/model.php';
require_once JPATH_TESTS . '/unit/core/model/fakemodel.php';

/**
 * @group 	View
 */
class FOFViewRawTest extends FtestCase
{
	protected $view = null;

	public function setUp()
	{
		parent::setUp();

		// Force a JDocumentHTML instance
		$this->saveFactoryState();
		JFactory::$document = JDocument::getInstance('html');

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
	 * @cover  	FOFViewRaw::getLists()
	 */
	public function testGetLists()
	{
		$lists = $this->view->getLists();
		$this->assertInternalType('object', $lists, 'getLists should return an object');
	}

	/**
	 * @cover  	FOFViewRaw::hasAjaxOrderingSupport()
	 */
	public function testHasAjaxOrderingSupportFalse()
	{
		$model = new FtestModel();
		$this->view->setModel($model, true);
		
		$this->assertEquals($this->view->hasAjaxOrderingSupport(), false, 'hasAjaxOrderingSupport should return false');
	}

	/**
	 * @cover  	FOFViewRaw::hasAjaxOrderingSupport()
	 */
	public function testHasAjaxOrderingSupport()
	{
		$model = new FtestModel(array('table' => 'Ftest', 'option' => ''));
		$this->view->setModel($model, true);
		
		$info = $this->view->hasAjaxOrderingSupport();

		$this->assertInternalType('array', $info, 'hasAjaxOrderingSupport should return an array');
		$this->assertEquals(array_key_exists('saveOrder', $info), true, 'hasAjaxOrderingSupport should have a saveOrder key');
		$this->assertEquals(array_key_exists('orderingColumn', $info), true, 'hasAjaxOrderingSupport should have a orderingColumn key');

		$model->setState('filter_order', 'ordering');

		$info = $this->view->hasAjaxOrderingSupport();

		$this->assertInternalType('array', $info, 'hasAjaxOrderingSupport should return an array');
		$this->assertEquals(array_key_exists('saveOrder', $info), true, 'hasAjaxOrderingSupport should have a saveOrder key');
	}

	/**
	 * @cover  	FOFViewRaw::hasAjaxOrderingSupport()
	 */
	public function testBadHasAjaxOrderingSupportFalse()
	{
		$model = new FtestFakeModel();
		$this->view->setModel($model, true);
		
		$this->assertEquals($this->view->hasAjaxOrderingSupport(), false, 'hasAjaxOrderingSupport should return false');

		$model = new FtestFakeModel2();
		$this->view->setModel($model, true);
		
		$this->assertEquals($this->view->hasAjaxOrderingSupport(), false, 'hasAjaxOrderingSupport should return false');
	}

	/**
	 * @cover  	FOFViewRaw::getPerms()
	 */
	public function testGetPerms()
	{
		$perms = $this->view->getPerms();
		$this->assertInternalType('object', $perms, 'getPerms should return an array');
	}

	/**
	 * @cover  	FOFViewRaw::__construct()
	 */
	public function testContructorWithConfigObject()
	{
		$config = array();		
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'Ftest'));

		$view = new FOFViewRaw((object)$config);
	}

	/**
	 * @cover  	FOFViewRaw::__construct()
	 */
	public function testContructorWithConfigWrong()
	{
		$view = new FOFViewRaw(0);
	}

	/**
	 * @cover  	FOFViewRaw::__construct()
	 */
	public function testContructorWithoutInput()
	{
		$config = array();				
		$view = new FOFViewRaw($config);
	}

	/**
	 * @cover  	FOFViewRaw::display()
	 */
	public function testDisplay()
	{
		$model = new FtestModel(array('table' => 'Ftest', 'option' => ''));
		$this->view->setModel($model, true);

		$this->view->addTemplatePath(JPATH_TESTS . '/unit/core/view/tmpl/');
		$this->view->setLayout('default');

		$this->expectOutputString('foobar');

		$this->view->display();
	}

	/**
	 * @cover  	FOFViewRaw::display()
	 */
	public function testDisplayWithFakeTask()
	{
		$model = new FtestModel(array('table' => 'Ftest', 'option' => ''));
		$model->setState('task', 'foo');

		$this->view->setModel($model, true);

		$this->view->addTemplatePath(JPATH_TESTS . '/unit/core/view/tmpl/');
		$this->view->setLayout('default');

		$this->expectOutputString('foobar');

		$this->view->display();
	}

	public function getViewsAndTasks()
	{
		$data = array();
		// View, task
		$data[] = array('cpanel', 'add');
		$data[] = array('cpanel', 'edit');
		$data[] = array('cpanel', 'read');
		$data[] = array('cpanel', 'browse');

		$data[] = array('foo', 'add');
		$data[] = array('foo', 'edit');
		$data[] = array('foo', 'read');
		$data[] = array('foo', 'browse');

		return $data;
	}

	/**
	 * @cover  			FOFViewRaw::display()
	 * @dataProvider	getViewsAndTasks
	 */
	public function testDisplayWithTasksAndViews($view, $task)
	{
		$model = new FtestModel(array('table' => 'Ftest', 'option' => ''));
		$model->setState('task', $task);
		
		$config = array();		
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $view));

		$view = new FOFViewRaw($config);

		$view->setModel($model, true);

		$view->addTemplatePath(JPATH_TESTS . '/unit/core/view/tmpl/');
		$view->setLayout('default');

		$this->expectOutputString('foobar');

		$view->display();
	}

}
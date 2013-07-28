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
			'template'		=> 'fake_test_template',
		);
		$attribute = new ReflectionProperty($application, 'template');
		$attribute->setAccessible(TRUE);
		$attribute->setValue($application, $template);

		// Replace the FOFPlatform with our fake one
		$this->saveFOFPlatform();
		$this->replaceFOFPlatform();

		return $this->getView();
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

}
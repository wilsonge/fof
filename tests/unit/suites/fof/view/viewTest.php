<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Inflector
 *
 * @copyright   Copyright (C) 2010 - 2012 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_TESTS . '/unit/core/view/view.php';
require_once JPATH_TESTS . '/unit/core/model/model.php';
require_once JPATH_TESTS . '/unit/core/renderer/renderer.php';

/**
 * @group 	View
 */
class FOFViewTest extends FtestCase
{
	protected $view = null;

	public function setUp()
	{
		$this->view = $this->getView();	
	}

	/**
	 * @cover FOFView::display
	 */
	public function testDisplay()
	{
		$this->view->addTemplatePath(JPATH_TESTS . '/unit/core/view/tmpl/');
		$this->view->setLayout('default');

		$this->expectOutputString('foobar');
		$this->view->display();
	}

	/**
	 * @cover FOFView::display
	 */
	public function testDisplayError()
	{
		$this->view->setLayout('error');
		
		try 
		{
			$error = $this->view->display();
			
			if ($error instanceof Exception) 
			{
				return;
			}
		} 
		catch (Exception $e) 
		{
			return;
		}

		 $this->fail('testDisplayError should have raised an exception');
	}

	/**
	 * @cover FOFView::testLoadAnyTemplate
	 */
	public function testLoadAnyTemplate()
	{
		$this->view->error = 'foobar';	
		echo $this->view->loadAnyTemplate('site:com_search/search/default_error');

		$this->expectOutputRegex('/(.)*foobar(.)*/');
	}

	/**
	 * @cover 	FOFView::getName()
	 */
	public function testGetName()
	{
		$this->assertEquals($this->view->getName(), 'Ftest', 'getName should return Ftest');
	}

	/**
	 * @cover 	FOFView::setLayout()
	 * @cover 	FOFView::getLayout()
	 */
	public function testSetLayout()
	{
		$this->view->setLayout('foobar');
		$this->assertEquals($this->view->getLayout(), 'foobar', 'setLayout should set the layout to foobar');
	}

	/**
	 * @cover 	FOFView::getLayoutTemplate()
	 */
	public function testGetLayoutTemplate()
	{
		$this->view->setLayout('template:foobar');
		$this->assertEquals($this->view->getLayoutTemplate(), 'template', 'getLayoutTemplate should return template');
	}

	/**
	 * @cover 	FOFView::setLayoutExt()
	 */
	public function testSetLayoutExt()
	{
		$this->view->setLayoutExt('foo');

		$this->assertAttributeEquals(
        	'foo',  /* expected value */
          	'_layoutExt',  /* attribute name */
          	$this->view 	/* object         */
        );
	}

	/**
	 * @cover 	FOFView::assign()
	 */
	public function testAssign()
	{
		$this->view->assign('foo', 'bar');
		$this->assertEquals($this->view->foo, 'bar', 'assign should set a foo variable in the view to bar');
	}

	/**
	 * @cover 	FOFView::assign()
	 */
	public function testAssignEmpty()
	{
		$this->view->assign(null);
	}

	/**
	 * @cover 	FOFView::assign()
	 */
	public function testAssignObject()
	{
		$obj = new stdClass();
		$obj->test1 = '123';
		$obj->test2 = '345';

		$this->view->assign($obj);

		$this->assertEquals($this->view->test1, '123', 'assign should set a test1 variable in the view to 123');
	}

	/**
	 * @cover 	FOFView::assign()
	 */
	public function testAssignArray()
	{
		$obj = array();
		$obj['test1'] = '123';
		$obj['test2'] = '345';

		$this->view->assign($obj);

		$this->assertEquals($this->view->test1, '123', 'assign should set a test1 variable in the view to 123');
	}

	/**
	 * @cover 	FOFView::assignRef()
	 */
	public function testAssignRef()
	{	
		$foo = 'foo';
		$this->view->assignRef('foo', $foo);
		$foo = 'bar';

		$this->assertEquals($this->view->foo, 'bar', 'assignRef should set a foo variable in the view to bar');
	}

	/**
	 * @cover 	FOFView::assignRef()
	 */
	public function testAssignRefWrong()
	{	
		$foo = 'foo';
		$result = $this->view->assignRef('_foo', $foo);

		$this->assertEquals($result, false, 'testAssignRefWrong should return false for _ prefixed vaariable names');

		$result = $this->view->assignRef(false, $foo);

		$this->assertEquals($result, false, 'testAssignRefWrong should return false for non string variable names');
	}

	/**
	 * @cover 	FOFView::escape()
	 */
	public function testEscape()
	{
		$string = '&';
		$this->assertEquals($this->view->escape($string), '&amp;', 'escape should encode & to &amp;');
	}

	/**
	 * @covers FOFView::get
	 */
	public function testGetFromModel()
	{
		$model = new FtestModel();
		$this->view->setModel($model, true);

		$this->assertEquals($this->view->get('foo'), 'foo', 'get should return foo');
		$this->assertEquals($this->view->get('bar'), null, 'get should return false');
	}

	/**
	 * @cover  	FOFView::_parseTemplatePath
	 */
	public function testParseTemplatePath()
	{
		$this->view->loadAnyTemplate('admin:test/default');
		$this->view->loadAnyTemplate('');
	}

	public function testGetRenderer()
	{
		$this->view->getRenderer();
	}

	/**
	 * @cover FOFView::get
	 */
	public function testGetFromView()
	{
		
		$this->view->foo = 'bar';

		$this->assertEquals($this->view->get('foo', ''), 'bar', 'get should return bar');
	}

	/**
	 * @cover 	FOFView::setModel()
	 * @cover 	FOFView::getModel()
	 */
	public function testSetModel()
	{
		
		$model = new FtestModel();
		$this->view->setModel($model, true);

		$this->assertEquals($this->view->getModel(), $model, 'getModel should return the model');
	}

	/**
	 * @covers FOFView::setRenderer
	 * @covers FOFView::getRenderer
	 */
	public function testSetRenderer()
	{
		
		$renderer = new FtestRenderer();
		$this->view->setRenderer($renderer);

		$this->assertEquals($this->view->getRenderer(), $renderer, 'setRenderer should set the renderer to the given renderer');
	}

	/**
	 * @covers FOFView::registerRenderer
	 */
	public function testRegisterRenderer()
	{
		
		$renderer = new FtestRenderer();
		$this->view->registerRenderer($renderer);

		$this->assertEquals($this->view->getRenderer(), $renderer, 'registerRenderer should get the renderer we set before');
	}

	public function testSetEscape()
	{
		$this->view->setEscape(array($this, 'exampleEscape'));
		$string = '&';
		$this->assertEquals($this->view->escape($string), '&amp;', 'escape should encode & to &amp;');
	}

	public function exampleEscape($value) 
	{
		return htmlentities($value);
	}

	/**
	 * @covers FOFView::setPreRender
	 */
	public function testSetPreRenderTrue()
	{
		
		$this->view->setPreRender(true);

		$this->assertAttributeEquals(
        	true,  /* expected value */
          	'doPreRender',  /* attribute name */
          	$this->view 	/* object         */
        );
	}

	/**
	 * @covers FOFView::setPreRender
	 */
	public function testSetPreRenderFalse()
	{
		
		$this->view->setPreRender(false);

		$this->assertAttributeEquals(
        	false,  /* expected value */
          	'doPreRender',  /* attribute name */
          	$this->view 	/* object         */
        );
	}

	/**
	 * @covers FOFView::setPostRender
	 */
	public function testSetPostRenderTrue()
	{
		
		$this->view->setPostRender(true);

		$this->assertAttributeEquals(
        	true,  /* expected value */
          	'doPostRender',  /* attribute name */
          	$this->view 	/* object         */
        );
	}

	/**
	 * @covers FOFView::loadHelper
	 * @covers FOFView::addHelperPath
	 */
	public function testLoadHelper()
	{
		
		$this->view->addHelperPath(JPATH_TESTS . '/unit/core/helper/');
		$this->view->loadHelper('helper');

		$this->assertEquals(class_exists('FtestHelper'), true, 'loadHelper should load the FtestHelper');
	}

	/**
	 * @covers FOFView::loadHelper
	 */
	public function testLoadNonExistingHelper()
	{
		$this->view->loadHelper('helper');
	}

	/**
	 * @covers FOFView::getViewOptionAndName
	 */
	public function testGetViewOptionAndName()
	{
		$this->assertEquals($this->view->getViewOptionAndName(), array('option' => 'com_foftest', 'view' => 'Ftest'), 'getViewOptionAndName');
	}

	/**
	 * @covers FOFView::setPostRender
	 */
	public function testSetPostRenderFalse()
	{
		
		$this->view->setPostRender(false);

		$this->assertAttributeEquals(
        	false,  /* expected value */
          	'doPostRender',  /* attribute name */
          	$this->view 	/* object         */
        );
	}

	

	/**
	 * @covers FOFView::__construct
	 */
	public function testViewWithConfigArray()
	{
		$config = array();		
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'Ftest'));
		$view = new FtestView($config);

		return $view;
	}

	/**
	 * @covers FOFView::__construct
	 */
	public function testViewWithConfigObject()
	{
		$config = array();		
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'Ftest'));
		$view = new FtestView((object)$config);
	}

	/**
	 * @covers FOFView::__construct
	 */
	public function testViewWithoutConfig()
	{
		$view = new FtestView(0);
	}

	/**
	 * @covers FOFView::__construct()
	 * @covers FOFView::getName()
	 */
	public function testViewWithoutName()
	{
		$config = array();
		$view = new FtestView($config);
		$view->getName();
	}

	/**
	 * @covers FOFView::__construct()
	 * @covers FOFView::getName()
	 */
	public function testViewWithViewName()
	{
		$config = array();
		$config['view'] = 'Ftest';
		$view = new FtestView($config);
		$view->getName();
	}

	/**
	 * @covers FOFView::__construct()
	 * @covers FOFView::getName()
	 */
	public function testViewWithoutNameAndView()
	{
		$config = array();
		
		try 
		{
			$view = new FtestFake($config);
			$view->getName();
		} 
		catch(Exception $e)
		{
			return;
		}

		$this->fail('testViewWithoutNameAndView should have thrown an exception');
	}

	/**
	 * @covers FOFView::__construct
	 */
	public function testViewWithCustomConfig()
	{
		$config = array();		
		$config['input'] = array('option' => 'com_foftest', 'view' => 'Ftest');
		$config['option'] = 'com_foftest';
		$config['view'] = null;
		$config['name'] = null;
		$config['charset'] = 'utf-8';
		$config['escape'] = null;
		$config['base_path'] = '';
		$config['template_path'] = '';
		$config['helper_path'] = '';
		$config['layout'] = 'default';

		$view = new FtestView($config);
	}

	protected function getView()
	{
		return $this->testViewWithConfigArray();
	}
}
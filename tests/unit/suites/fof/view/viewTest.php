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
	public function testGetName()
	{
		$view = $this->getView();
		$this->assertEquals($view->getName(), 'Ftest', 'getName should return Ftest');
	}

	public function testSetLayout()
	{
		$view = $this->getView();

		$view->setLayout('foobar');
		$this->assertEquals($view->getLayout(), 'foobar', 'setLayout should set the layout to foobar');
	}

	public function testGetLayout()
	{
		$view = $this->getView();

		$view->setLayout('foobar');
		$this->assertEquals($view->getLayout(), 'foobar', 'getLayout should return foobar');
	}

	public function testGetLayoutTemplate()
	{
		$view = $this->getView();

		$view->setLayout('template:foobar');
		$this->assertEquals($view->getLayoutTemplate(), 'template', 'getLayoutTemplate should return template');
	}

	public function testSetLayoutExt()
	{
		$view = $this->getView();

		$view->setLayoutExt('foo');

		$this->assertAttributeEquals(
        	'foo',  /* expected value */
          	'_layoutExt',  /* attribute name */
          	$view 	/* object         */
        );
	}

	public function testAssign()
	{
		$view = $this->getView();
		
		$view->assign('foo', 'bar');
		$this->assertEquals($view->foo, 'bar', 'assign should set a foo variable in the view to bar');
	}

	public function testAssignRef()
	{
		$view = $this->getView();
		
		$foo = 'foo';
		$view->assignRef('foo', $foo);
		$foo = 'bar';

		$this->assertEquals($view->foo, 'bar', 'assignRef should set a foo variable in the view to bar');
	}

	public function testEscape()
	{
		$view = $this->getView();
		
		$string = '&';
		$this->assertEquals($view->escape($string), '&amp;', 'escape should encode & to &amp;');
	}

	public function testGetFromModel()
	{
		$view = $this->getView();
		$model = new FtestModel();
		$view->setModel($model, true);

		$this->assertEquals($view->get('foo'), 'foo', 'get should return foo');
	}

	public function testGetFromView()
	{
		$view = $this->getView();
		$view->foo = 'bar';

		$this->assertEquals($view->get('foo', ''), 'bar', 'get should return bar');
	}

	public function testSetModel()
	{
		$view = $this->getView();
		$model = new FtestModel();
		$view->setModel($model, true);

		$this->assertEquals($view->getModel(), $model, 'getModel should return the model');
	}

	public function testGetModel()
	{
		$view = $this->getView();
		$model = new FtestModel();
		$view->setModel($model, true);

		$this->assertEquals($view->getModel(), $model, 'setModel should set the model to the given model');
	}

	public function testSetRenderer()
	{
		$view = $this->getView();
		$renderer = new FtestRenderer();
		$view->setRenderer($renderer);

		$this->assertEquals($view->getRenderer(), $renderer, 'setRenderer should set the renderer to the given renderer');
	}

	public function testGetRenderer()
	{
		$view = $this->getView();
		$renderer = new FtestRenderer();
		$view->registerRenderer($renderer);

		$this->assertEquals($view->getRenderer(), $renderer, 'getRenderer should get the renderer we set before');
	}

	public function testRegisterRenderer()
	{
		$view = $this->getView();
		$renderer = new FtestRenderer();
		$view->registerRenderer($renderer);

		$this->assertEquals($view->getRenderer(), $renderer, 'registerRenderer should get the renderer we set before');
	}

	public function testSetPreRenderTrue()
	{
		$view = $this->getView();
		$view->setPreRender(true);

		$this->assertAttributeEquals(
        	true,  /* expected value */
          	'doPreRender',  /* attribute name */
          	$view 	/* object         */
        );
	}

	public function testSetPreRenderFalse()
	{
		$view = $this->getView();
		$view->setPreRender(false);

		$this->assertAttributeEquals(
        	false,  /* expected value */
          	'doPreRender',  /* attribute name */
          	$view 	/* object         */
        );
	}

	public function testSetPostRenderTrue()
	{
		$view = $this->getView();
		$view->setPostRender(true);

		$this->assertAttributeEquals(
        	true,  /* expected value */
          	'doPostRender',  /* attribute name */
          	$view 	/* object         */
        );
	}

	/**
	 * @covers loadHelper
	 * @covers addHelperPath
	 */
	public function testLoadHelper()
	{
		$view = $this->getView();
		$view->addHelperPath(JPATH_TESTS . '/unit/core/helper/');
		$view->loadHelper('helper');

		$this->assertEquals(class_exists('FtestHelper'), true, 'loadHelper should load the FtestHelper');
	}

	public function testGetViewOptionAndName()
	{
		$view = $this->getView();

		$this->assertEquals($view->getViewOptionAndName(), array('option' => 'com_foftest', 'view' => 'Ftest'), 'getViewOptionAndName');
	}

	public function testSetPostRenderFalse()
	{
		$view = $this->getView();
		$view->setPostRender(false);

		$this->assertAttributeEquals(
        	false,  /* expected value */
          	'doPostRender',  /* attribute name */
          	$view 	/* object         */
        );
	}

	protected function getView()
	{
		$config = array();
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'Ftest'));
		
		$view = new FtestView($config);
		return $view;
	}
}
<?php
/**
 * @group 	View
 */
class FOFViewHtmlTest extends FtestCase
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
		if (FOFPlatform::getInstance()->checkVersion(JVERSION, '3.2.0', 'ge'))
		{
			$application->initialise();
		}

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

		$view = new FOFViewHtml($config);

		return $view;
	}

	/**
	 *	@cover 	FOFViewHtml::__costruct()
	 */
	public function testConstructor()
	{
		return $this->view;
	}

	/**
	 * @cover  	FOFViewHtml::__construct()
	 */
	public function testContructorWithConfigObject()
	{
		$config = array();		
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => 'Ftest'));

		$view = new FOFViewHtml((object)$config);
	}

	/**
	 * @cover  	FOFViewHtml::__construct()
	 */
	public function testContructorWithConfigWrong()
	{
		$view = new FOFViewHtml(0);
	}

	/**
	 * @cover  	FOFViewHtml::__construct()
	 */
	public function testContructorWithoutInput()
	{
		$config = array();				
		$view = new FOFViewHtml($config);
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
	 * @cover  			FOFViewHtml::display()
	 * @dataProvider	getViewsAndTasks
	 */
	public function testDisplayWithTasksAndViews($view, $task)
	{
		$model = new FtestModel(array('table' => 'Ftest', 'option' => ''));
		$model->setState('task', $task);
		
		$config = array();		
		$config['input'] = new FOFInput(array('option' => 'com_foftest', 'view' => $view));
		$config['view']	= $view;
		$config['option']	= 'com_foftest';

		$view = new FOFViewHtml($config);

		$view->setModel($model, true);

		$view->addTemplatePath(JPATH_TESTS . '/unit/core/view/tmpl/');
		$view->setLayout('default');

		$this->expectOutputRegex('/(.)*foobar(.)*/');

		$view->display();
	}
}
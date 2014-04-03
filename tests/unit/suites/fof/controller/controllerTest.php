<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'controllerDataprovider.php';
require_once JPATH_TESTS.'/unit/core/application/route.php';

class FOFControllerTest extends FtestCaseDatabase
{
    protected function setUp()
    {
        $loadDataset = true;
        $annotations = $this->getAnnotations();

        // Do I need a dataset for this set or not?
        if(isset($annotations['method']) && isset($annotations['method']['preventDataLoading']))
        {
            $loadDataset = false;
        }

        parent::setUp($loadDataset);

        FOFPlatform::forceInstance(null);
        FOFTable::forceInstance(null);
    }

    /**
     * @group           FOFController
     * @group           controllerCreateFilename
     * @covers          FOFController::createFilename
     * @dataProvider    getTestCreateFilename
     *
     * @preventDataLoading
     */
    public function testCreateFilename($test, $check)
    {
        $method = new ReflectionMethod('FOFController', 'createFilename');
        $method->setAccessible(true);
        $filename = $method->invoke(null, $test['type'], $test['parts']);

        $this->assertEquals($check['filename'], $filename, 'FOFController::createFilename created the wrong filename');
    }

    /**
     * @group           FOFController
     * @group           controllerBrowse
     * @covers          FOFController::browse
     * @dataProvider    getTestBrowse
     *
     * @preventDataLoading
     */
    public function testBrowse($test, $check)
    {
        $controller = $this->getMock('FOFController', array('display', 'getModel'));
        $controller->expects($this->any())->method('display')->with($this->equalTo($check['cache']));

        $taskCache = new ReflectionProperty($controller, 'cacheableTasks');
        $taskCache->setAccessible(true);
        $taskCache->setValue($controller, $test['cache']);

        $layout = new ReflectionProperty($controller, 'layout');
        $layout->setAccessible(true);
        $layout->setValue($controller, $test['layout']);

        $model      = $this->getMock('FOFModel', array('setState'));
        $model->expects($this->any())->method('setState')->with($this->equalTo('form_name'), $this->equalTo($check['form_name']));

        $controller->expects($this->any())->method('getModel')->will($this->returnValue($model));

        $return = $controller->browse();

        $this->assertEquals($check['return'], $return, 'FOFController::browse returned the wrong value');
    }

    /**
     * @group           FOFController
     * @group           controllerRead
     * @covers          FOFController::read
     * @dataProvider    getTestRead
     */
    public function testRead($test, $check)
    {
        $controller = $this->getMock('FOFController', array('display', 'getModel'));
        $controller->expects($this->any())->method('display')->with($this->equalTo($check['cache']));

        $taskCache = new ReflectionProperty($controller, 'cacheableTasks');
        $taskCache->setAccessible(true);
        $taskCache->setValue($controller, $test['cache']);

        $layout = new ReflectionProperty($controller, 'layout');
        $layout->setAccessible(true);
        $layout->setValue($controller, $test['layout']);

        // I have to load the record here since in the dataprovider the table is not populated yet
        if($test['loadid'])
        {
            $test['item']->load($test['loadid']);
        }

        $model      = $this->getMock('FOFModel', array('setState', 'getId', 'getItem'));
        $model->expects($this->any())->method('getId')->will($this->returnValue($test['id']));
        $model->expects($this->any())->method('getItem')->will($this->returnValue($test['item']));
        $model->expects($this->any())->method('setState')->with($this->equalTo('form_name'), $this->equalTo($check['form_name']));

        $controller->expects($this->any())->method('getModel')->will($this->returnValue($model));

        $return = $controller->read();

        $this->assertEquals($check['return'], $return, 'FOFController::read returned the wrong value');
    }

    /**
     * @group           FOFController
     * @group           controllerAdd
     * @covers          FOFController::add
     * @dataProvider    getTestAdd
     *
     * @preventDataLoading
     */
    public function testAdd($test, $check)
    {
        $controller = $this->getMock('FOFController', array('display', 'getModel'));
        $controller->expects($this->any())->method('display')->with($this->equalTo($check['cache']));

        $taskCache = new ReflectionProperty($controller, 'cacheableTasks');
        $taskCache->setAccessible(true);
        $taskCache->setValue($controller, $test['cache']);

        $layout = new ReflectionProperty($controller, 'layout');
        $layout->setAccessible(true);
        $layout->setValue($controller, $test['layout']);


        $model      = $this->getMock('FOFModel', array('setState', 'getItem'));
        $model->expects($this->any())->method('getItem')->will($this->returnValue($test['item']));
        $model->expects($this->any())->method('setState')->with($this->equalTo('form_name'), $this->equalTo($check['form_name']));

        $controller->expects($this->any())->method('getModel')->will($this->returnValue($model));

        $return = $controller->add();

        $this->assertEquals($check['return'], $return, 'FOFController::add returned the wrong value');
    }

    /**
     * @group           FOFController
     * @group           controllerEdit
     * @covers          FOFController::edit
     * @dataProvider    getTestEdit
     */
    public function testEdit($test, $check)
    {
        $config = array(
            'input' => new FOFInput(array(
                    'option'    => 'com_foftest',
                    'view'      => 'foobar',
                    'returnurl' => $test['returnurl']
                ))
        );

        $controller = $this->getMock('FOFController', array('display', 'getModel', 'setRedirect'), array($config));
        $controller->expects($this->any())->method('display')->with($this->equalTo($check['cache']));

        if($test['checkout'])
        {
            $controller->expects($this->never())->method('setRedirect');
        }
        else
        {
            $controller->expects($this->once())->method('setRedirect')->with(
                $this->equalTo($check['returnUrl']),
                $this->equalTo(''),
                $this->equalTo('error')
            );
        }

        $taskCache = new ReflectionProperty($controller, 'cacheableTasks');
        $taskCache->setAccessible(true);
        $taskCache->setValue($controller, $test['cache']);

        $layout = new ReflectionProperty($controller, 'layout');
        $layout->setAccessible(true);
        $layout->setValue($controller, $test['layout']);

        // I have to load the record here since in the dataprovider the table is not populated yet
        if($test['loadid'])
        {
            $test['item']->load($test['loadid']);
        }

        $model      = $this->getMock('FOFModel', array('setState', 'getId', 'getItem', 'checkout'));
        $model->expects($this->any())->method('getId')->will($this->returnValue($test['id']));
        $model->expects($this->any())->method('getItem')->will($this->returnValue($test['item']));
        $model->expects($this->any())->method('checkout')->will($this->returnValue($test['checkout']));
        $model->expects($this->any())->method('setState')->with($this->equalTo('form_name'), $this->equalTo($check['form_name']));

        $controller->expects($this->any())->method('getModel')->will($this->returnValue($model));

        $return = $controller->edit();

        $this->assertEquals($check['return'], $return, 'FOFController::edit returned the wrong value');
    }

    /**
     * @group           FOFController
     * @group           controllerCopy
     * @covers          FOFController::copy
     * @dataProvider    getTestCopy
     *
     * @preventDataLoading
     */
    public function testCopy($test, $check)
    {
        $config = array(
            'input' => new FOFInput(array(
                    'option'    => 'com_foftest',
                    'view'      => 'foobar',
                    'returnurl' => $test['returnurl']
                ))
        );

        $controller = $this->getMock('FOFController', array('getModel', 'setRedirect', '_csrfProtection'), array($config));
        $controller->expects($this->any())->method('_csrfProtection')->will($this->returnValue(null));

        if($test['copy'])
        {
            $controller->expects($this->once())->method('setRedirect')->with(
                $this->equalTo($check['returnUrl'])
            );
        }
        else
        {
            $controller->expects($this->once())->method('setRedirect')->with(
                $this->equalTo($check['returnUrl']),
                $this->equalTo(''),
                $this->equalTo('error')
            );
        }

        $model = $this->getMock('FOFModel', array('getId', 'copy'));
        $model->expects($this->any())->method('getId')->will($this->returnValue(true));
        $model->expects($this->any())->method('copy')->will($this->returnValue($test['copy']));

        $controller->expects($this->any())->method('getModel')->will($this->returnValue($model));

        $return = $controller->copy();

        $this->assertEquals($check['return'], $return, 'FOFController::copy returned the wrong value');
    }

    /**
     * @group           FOFController
     * @group           controllerCancel
     * @covers          FOFController::cancel
     * @dataProvider    getTestCancel
     *
     * @preventDataLoading
     */
    public function testCancel($test, $check)
    {
        $config = array(
            'input' => new FOFInput(array(
                    'option'    => 'com_foftest',
                    'view'      => 'foobar',
                    'returnurl' => $test['returnurl']
                ))
        );

        $hackedSession = new JSession;

        // Manually set the session as active
        $property = new ReflectionProperty($hackedSession, '_state');
        $property->setAccessible(true);
        $property->setValue($hackedSession, 'active');

        $session = serialize(array('foftest_foobar_id' => 2, 'title' => 'Title from session'));

        // We're in CLI and no $_SESSION variable? No problem, I'll manually create it!
        // I'm going to hell for doing this...
        $_SESSION['__default']['com_foftest.foobars.savedata'] = $session;

        JFactory::$session = $hackedSession;

        $controller = $this->getMock('FOFController', array('getModel', 'setRedirect'), array($config));
        $controller->expects($this->any())->method('_csrfProtection')->will($this->returnValue(null));

        $controller->expects($this->once())->method('setRedirect')->with(
            $this->equalTo($check['returnUrl'])
        );

        $model = $this->getMock('FOFModel', array('getId', 'copy'), array($config));
        $model->expects($this->any())->method('getId')->will($this->returnValue(true));
        $model->expects($this->any())->method('checkin')->will($this->returnValue($test['checkin']));

        $controller->expects($this->any())->method('getModel')->will($this->returnValue($model));

        $return = $controller->cancel();

        $this->assertEquals($check['return'], $return, 'FOFController::cancel returned the wrong value');

        $this->assertArrayNotHasKey('com_foftest.foobars.savedata', $_SESSION['__default'], 'FOFController::cancel should wipe saved session data');

        // Let's remove any evidence...
        unset($_SESSION);
    }


    /**
     * @group           FOFController
     * @group           controllerOrderdown
     * @covers          FOFController::orderdown
     * @dataProvider    getTestOrderDown
     *
     * @preventDataLoading
     */
    public function testOrderdown($test, $check)
    {
        $config = array(
            'input' => new FOFInput(array(
                    'option'    => 'com_foftest',
                    'view'      => 'foobar',
                    'returnurl' => $test['returnurl']
                ))
        );

        $controller = $this->getMock('FOFController', array('getModel', 'setRedirect', '_csrfProtection'), array($config));
        $controller->expects($this->any())->method('_csrfProtection')->will($this->returnValue(null));

        if($test['move'])
        {
            $controller->expects($this->once())->method('setRedirect')->with(
                $this->equalTo($check['returnUrl'])
            );
        }
        else
        {
            $controller->expects($this->once())->method('setRedirect')->with(
                $this->equalTo($check['returnUrl']),
                $this->equalTo(''),
                $this->equalTo('error')
            );
        }

        $model = $this->getMock('FOFModel', array('getId', 'move'));
        $model->expects($this->any())->method('getId')->will($this->returnValue(true));
        $model->expects($this->any())->method('move')->will($this->returnValue($test['move']));

        $controller->expects($this->any())->method('getModel')->will($this->returnValue($model));

        $return = $controller->orderdown();

        $this->assertEquals($check['return'], $return, 'FOFController::orderdown returned the wrong value');
    }

    /**
     * @group           FOFController
     * @group           controllerOrderup
     * @covers          FOFController::orderup
     * @dataProvider    getTestOrderUp
     *
     * @preventDataLoading
     */
    public function testOrderup($test, $check)
    {
        $config = array(
            'input' => new FOFInput(array(
                    'option'    => 'com_foftest',
                    'view'      => 'foobar',
                    'returnurl' => $test['returnurl']
                ))
        );

        $controller = $this->getMock('FOFController', array('getModel', 'setRedirect', '_csrfProtection'), array($config));
        $controller->expects($this->any())->method('_csrfProtection')->will($this->returnValue(null));

        if($test['move'])
        {
            $controller->expects($this->once())->method('setRedirect')->with(
                $this->equalTo($check['returnUrl'])
            );
        }
        else
        {
            $controller->expects($this->once())->method('setRedirect')->with(
                $this->equalTo($check['returnUrl']),
                $this->equalTo(''),
                $this->equalTo('error')
            );
        }

        $model = $this->getMock('FOFModel', array('getId', 'move'));
        $model->expects($this->any())->method('getId')->will($this->returnValue(true));
        $model->expects($this->any())->method('move')->will($this->returnValue($test['move']));

        $controller->expects($this->any())->method('getModel')->will($this->returnValue($model));

        $return = $controller->orderup();

        $this->assertEquals($check['return'], $return, 'FOFController::orderdup returned the wrong value');
    }

    /**
     * @group           FOFController
     * @group           controllerRemove
     * @covers          FOFController::remove
     * @dataProvider    getTestRemove
     *
     * @preventDataLoading
     */
    public function testRemove($test, $check)
    {
        $config = array(
            'input' => new FOFInput(array(
                    'option'    => 'com_foftest',
                    'view'      => 'foobar',
                    'returnurl' => $test['returnurl']
                ))
        );

        $controller = $this->getMock('FOFController', array('getModel', 'setRedirect', '_csrfProtection'), array($config));
        $controller->expects($this->any())->method('_csrfProtection')->will($this->returnValue(null));

        if($test['remove'])
        {
            $controller->expects($this->once())->method('setRedirect')->with(
                $this->equalTo($check['returnUrl'])
            );
        }
        else
        {
            $controller->expects($this->once())->method('setRedirect')->with(
                $this->equalTo($check['returnUrl']),
                $this->equalTo(''),
                $this->equalTo('error')
            );
        }

        $model = $this->getMock('FOFModel', array('getId', 'delete'));
        $model->expects($this->any())->method('getId')->will($this->returnValue(true));
        $model->expects($this->any())->method('delete')->will($this->returnValue($test['remove']));

        $controller->expects($this->any())->method('getModel')->will($this->returnValue($model));

        $return = $controller->remove();

        $this->assertEquals($check['return'], $return, 'FOFController::remove returned the wrong value');
    }

	/**
	 * @group           FOFController
	 * @group           controllerSetRedirect
	 * @covers          FOFController::setRedirect
	 * @dataProvider    getTestSetRedirect
	 *
	 * @preventDataLoading
	 */
	public function testSetRedirect($test, $check)
	{
		$config = array(
			'autoRouting' => $test['route'],
			'input' => new FOFInput(array(
					'option'    => 'com_foftest',
					'view'      => 'foobar'
				))
		);

		$platform = $this->getMock('FOFIntegrationJoomlaPlatform', array('isBackend'));
		$platform->expects($this->any())->method('isBackend')->will($this->returnValue($test['backend']));

		FOFPlatform::forceInstance($platform);

		$controller = new FOFController($config);

		$type = new ReflectionProperty($controller, 'messageType');
		$type->setAccessible(true);

		if(isset($test['previousType']))
		{
			$type->setValue($controller, $test['previousType']);
		}

		$return = $controller->setRedirect($test['url'], $test['msg'], $test['type']);

		$this->assertInstanceOf('FOFController', $return, 'FOFController::setRedirect should return an instance of FOFController');

		$redirect = new ReflectionProperty($controller, 'redirect');
		$redirect->setAccessible(true);
		$this->assertEquals($check['redirect'], $redirect->getValue($controller), 'FOFController::setController created the wrong redirect URL');


		$this->assertEquals($check['type'], $type->getValue($controller), 'FOFController::setController set the wrong message type');

		$message = new ReflectionProperty($controller, 'message');
		$message->setAccessible(true);
		$this->assertEquals($check['message'], $message->getValue($controller), 'FOFController::setController set the wrong message');
	}

    public function getTestCreateFilename()
    {
        return ControllerDataprovider::getTestCreateFilename();
    }

    public function getTestBrowse()
    {
        return ControllerDataprovider::getTestBrowse();
    }

    public function getTestRead()
    {
        return ControllerDataprovider::getTestRead();
    }

    public function getTestAdd()
    {
        return ControllerDataprovider::getTestAdd();
    }

    public function getTestEdit()
    {
        return ControllerDataprovider::getTestEdit();
    }

    public function getTestCopy()
    {
        return ControllerDataprovider::getTestCopy();
    }

    public function getTestCancel()
    {
        return ControllerDataprovider::getTestCancel();
    }

    public function getTestOrderDown()
    {
        return ControllerDataprovider::getTestOrderDown();
    }

    public function getTestOrderUp()
    {
        return ControllerDataprovider::getTestOrderUp();
    }

    public function getTestRemove()
    {
        return ControllerDataprovider::getTestRemove();
    }

	public function getTestSetRedirect()
	{
		return ControllerDataprovider::getTestSetRedirect();
	}
}

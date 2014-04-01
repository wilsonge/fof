<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'controllerDataprovider.php';

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
}

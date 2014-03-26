<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Toolbar
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

use org\bovigo\vfs\vfsStream;

/*
 * Since Joomla is using static calls to JToolbarHelper, it would be impossible to test. However we have created
 * a stub that will replace the original class and intercept all the function calls. In this way we can check if
 * a method was called and the arguments passed
 */
require_once 'toolbarHerlperStub.php';
require_once 'toolbarDataprovider.php';

class FOFToolbarTest extends FtestCase
{
    protected function setUp()
    {
        parent::setUp();

        FOFPlatform::forceInstance(null);
        JToolbarHelper::resetStack();
    }

    /**
     * @group           FOFToolbar
     * @dataProvider    getTestOnCpanelsBrowse
     * @covers          FOFToolbar::onCpanelsBrowse
     */
    public function testOnCpanelsBrowse($test, $check)
    {
        $platform = $this->getMock('FOFIntegrationJoomlaPlatform', array('isBackend'));
        $platform->expects($this->any())->method('isBackend')->will($this->returnValue($test['isBackend']));

        FOFPlatform::forceInstance($platform);

        $config = array(
            'renderFrontendSubmenu' => $test['submenu'],
            'renderFrontendButtons' => $test['buttons'],
            'input' => new FOFInput(array('option' => 'com_foftests'))
        );

        $toolbar = $this->getMock('FOFToolbar', array('renderSubmenu'), array($config));
        $toolbar->expects($this->any())->method('renderSubmenu')->will($this->returnValue(null));

        if($test['callSubmenu'])
        {
            $toolbar->expects($this->any())->method('renderSubmenu');
        }
        else
        {
            $toolbar->expects($this->never())->method('renderSubmenu');
        }

        $toolbar->onCpanelsBrowse();

        $invokedMethods = JToolbarHelper::getStack();

        $this->assertEquals($check['methods'], $invokedMethods, 'FOFToolbar::onCpanelsBrowse called the wrong methods');
    }

    /**
     * @group           FOFToolbar
     * @group           toolbarOnBrowse
     * @dataProvider    getTestOnBrowse
     * @covers          FOFToolbar::onBrowse
     */
    public function testOnBrowse($test, $check)
    {
        $platform = $this->getMock('FOFIntegrationJoomlaPlatform', array('isBackend'));
        $platform->expects($this->any())->method('isBackend')->will($this->returnValue($test['isBackend']));

        FOFPlatform::forceInstance($platform);

        $config = array(
            'renderFrontendSubmenu' => $test['submenu'],
            'renderFrontendButtons' => $test['buttons'],
            'input' => new FOFInput(array('option' => 'com_foftests', 'view' => $test['view']))
        );

        $toolbar = $this->getMock('FOFToolbar', array('renderSubmenu'), array($config));
        $toolbar->expects($this->any())->method('renderSubmenu')->will($this->returnValue(null));

        $toolbar->perms = (object)$test['perms'];

        if($test['callSubmenu'])
        {
            $toolbar->expects($this->any())->method('renderSubmenu');
        }
        else
        {
            $toolbar->expects($this->never())->method('renderSubmenu');
        }

        $toolbar->onBrowse();

        $invokedMethods = JToolbarHelper::getStack();

        $this->assertEquals($check['methods'], $invokedMethods, 'FOFToolbar::onBrowse called the wrong methods');
    }

    /**
     * @group           FOFToolbar
     * @group           toolbarOnRead
     * @dataProvider    getTestOnRead
     * @covers          FOFToolbar::onRead
     */
    public function testOnRead($test, $check)
    {
        $platform = $this->getMock('FOFIntegrationJoomlaPlatform', array('isBackend'));
        $platform->expects($this->any())->method('isBackend')->will($this->returnValue($test['isBackend']));

        FOFPlatform::forceInstance($platform);

        $config = array(
            'renderFrontendSubmenu' => $test['submenu'],
            'renderFrontendButtons' => $test['buttons'],
            'input' => new FOFInput(array('option' => 'com_foftests', 'view' => $test['view']))
        );

        $toolbar = $this->getMock('FOFToolbar', array('renderSubmenu'), array($config));
        $toolbar->expects($this->any())->method('renderSubmenu')->will($this->returnValue(null));

        if($test['callSubmenu'])
        {
            $toolbar->expects($this->any())->method('renderSubmenu');
        }
        else
        {
            $toolbar->expects($this->never())->method('renderSubmenu');
        }

        $toolbar->onRead();

        $invokedMethods = JToolbarHelper::getStack();

        $this->assertEquals($check['methods'], $invokedMethods, 'FOFToolbar::onRead called the wrong methods');
    }

    /**
     * @group           FOFToolbar
     * @group           toolbarOnAdd
     * @dataProvider    getTestOnAdd
     * @covers          FOFToolbar::onAdd
     */
    public function testOnAdd($test, $check)
    {
        $platform = $this->getMock('FOFIntegrationJoomlaPlatform', array('isBackend'));
        $platform->expects($this->any())->method('isBackend')->will($this->returnValue($test['isBackend']));

        FOFPlatform::forceInstance($platform);

        $config = array(
            'renderFrontendButtons' => $test['buttons'],
            'input' => new FOFInput(array('option' => 'com_foftests', 'view' => $test['view']))
        );

        $toolbar = new FOFToolbar($config);

        $toolbar->perms = (object)$test['perms'];

        $toolbar->onAdd();

        $invokedMethods = JToolbarHelper::getStack();

        $this->assertEquals($check['methods'], $invokedMethods, 'FOFToolbar::onAdd called the wrong methods');
    }

    /**
     * @group           FOFToolbar
     * @group           toolbarGetMyViews
     * @dataProvider    getTestGetMyViews
     * @covers          FOFToolbar::getMyViews
     */
    public function testGetMyViews($test)
    {
        // First of all I stub the filesystem object, so it won't strip out the protocol part
        $filesystem = $this->getMock('FOFIntegrationJoomlaPlatform', array('fileExists'));
        $filesystem->expects($this->any())
                   ->method('fileExists')
                   ->will($this->returnCallback(function($file){ return is_file($file);}));

        $platform = $this->getMock('FOFIntegrationJoomlaPlatform', array('getComponentBaseDirs'));

        // Then I have to trick the platform, providing a template path
        $platform->expects($this->any())
                 ->method('getComponentBaseDirs')
                 ->will($this->returnValue(JPATH_ROOT.'/administrator/com_foftest/views'));

        // Finally, force the platform to return my mocked object
        $platform->setIntegrationObject('filesystem', $filesystem);

        FOFPlatform::forceInstance($platform);

        $paths = array();

        foreach($test['paths'] as $path)
        {
            $parts = explode('/', $path);
            $last = array_pop($parts);

            if(strpos($last, '.') === false)
            {
                $parts[] = $last;
            }

            $paths[] = vfsStream::url('root/'.implode('/', $parts));
        }

        vfsStream::setup('root', null, $test['structure']);

        $config = array(
            'input' => new FOFInput(array('option' => 'com_foftests'))
        );

        $toolbar = new FOFToolbar($config);
        $method  = new ReflectionMethod($toolbar, 'getMyViews');
        $method->setAccessible(true);

        $views = $method->invoke($toolbar);
    }

    public function getTestOnCpanelsBrowse()
    {
        return ToolbarDataprovider::getTestOnCpanelsBrowse();
    }

    public function getTestOnBrowse()
    {
        return ToolbarDataprovider::getTestOnBrowse();
    }

    public function getTestOnRead()
    {
        return ToolbarDataprovider::getTestOnRead();
    }

    public function getTestOnAdd()
    {
        return ToolbarDataprovider::getTestOnAdd();
    }

    public function getTestGetMyViews()
    {
        return ToolbarDataprovider::getTestGetMyViews();
    }
}
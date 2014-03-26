<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Toolbar
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

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
}
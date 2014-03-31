<?php
/**
 * @package	    FrameworkOnFramework.UnitTest
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2010 - 2014 Akeeba Ltd. All rights reserved.
 * @license	    GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once 'controllerDataprovider.php';

class FOFControllerTest extends FtestCase
{
    /**
     * @group           FOFController
     * @group           controllerCreateFilename
     * @covers          FOFController::createFilename
     * @dataProvider    getTestCreateFilename
     */
    public function testCreateFilename($test, $check)
    {
        $method = new ReflectionMethod('FOFController', 'createFilename');
        $method->setAccessible(true);
        $filename = $method->invoke(null, $test['type'], $test['parts']);

        $this->assertEquals($check['filename'], $filename, 'FOFController::createFilename created the wrong filename');
    }

    public function getTestCreateFilename()
    {
        return ControllerDataprovider::getTestCreateFilename();
    }
}

<?php

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Form;
use FOF30\Tests\Helpers\FOFTestCase;

require_once __DIR__.'/AccessLevelDataprovider.php';

class AccessLevelTest extends FOFTestCase
{
    /**
     * @group           Field
     * @group           AccessLevelGetFieldContents
     * @covers          FOF30\Form\Field\AccessLevel::getFieldContents
     * @dataProvider    AccessLevelDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'AccessLevel::getFieldContents %s - Case: '.$check['case'];

        $field = $this->getMock('FOF30\Form\Field\AccessLevel', array('getOptions'));
        $field->method('getOptions')->willReturn($test['mock']['options']);

        $form = new Form(static::$container, 'Foobar');
        $field->setForm($form);

        // Registered access level
        $field->value = $test['value'];

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}
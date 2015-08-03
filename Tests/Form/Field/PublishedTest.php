<?php

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Published;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__.'/PublishedDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Published::<private>
 * @covers  FOF30\Form\Field\Published::<protected>
 */
class PublishedTest extends FOFTestCase
{
    /**
     * @group           Published
     * @group           Published__get
     * @covers          FOF30\Form\Field\Published::__get
     * @dataProvider    PublishedDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Published', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Published
     * @group           PublishedGetStatic
     * @covers          FOF30\Form\Field\Published::getStatic
     * @dataProvider    PublishedDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $msg = 'Published::getStatic %s - Case: '.$check['case'];

        $field = $this->getMock('FOF30\Form\Field\Published', array('getOptions'));
        $field->method('getOptions')->willReturn(array(
            array('value' => 1, 'text' => 'Published'),
            array('value' => 0, 'text' => 'Unpublished'),
        ));

        $field->class = 'foo-class';
        $field->id    = 'foo-id';
        $field->value = $test['value'];

        $result = $field->getStatic();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}
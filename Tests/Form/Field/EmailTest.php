<?php

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Email;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__.'/EmailDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Email::<private>
 * @covers  FOF30\Form\Field\Email::<protected>
 */
class EmailTest extends FOFTestCase
{
    /**
     * @group           Email
     * @group           Email__get
     * @covers          FOF30\Form\Field\Email::__get
     * @dataProvider    EmailDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Email', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Email
     * @group           EmailGetStatic
     * @covers          FOF30\Form\Field\Email::getStatic
     * @dataProvider    EmailDataprovider::getTestGetStatic
     */
    public function testGetStatic($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Email', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('id' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Email" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->getStatic();
    }

    /**
     * @group           Email
     * @group           EmailGetRepeatable
     * @covers          FOF30\Form\Field\Email::getRepeatable
     * @dataProvider    EmailDataprovider::getTestGetRepeatable
     */
    public function testGetRepeatable($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Email', array('getInput', 'getFieldContents'));
        $field->expects($this->exactly($check['input']))->method('getInput');
        $field->expects($this->exactly($check['contents']))->method('getFieldContents')->with(array('class' => 'foo'));

        $field->id = 'foo';

        $data  = '<field type="Email" name="foobar" ';

        if($test['legacy'])
        {
            $data .= 'legacy="true"';
        }

        $data .= ' />';
        $xml  = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $field->getRepeatable();
    }

    /**
     * @group           Email
     * @group           EmailGetFieldContents
     * @covers          FOF30\Form\Field\Email::getFieldContents
     * @dataProvider    EmailDataprovider::getTestGetFieldContents
     */
    public function testGetFieldContents($test, $check)
    {
        $msg = 'Email::getFieldContents %s - Case: '.$check['case'];

        $field = $this->getMock('FOF30\Form\Field\Email', array('parseFieldTags'));
        $field->method('parseFieldTags')->willReturn('__PARSED__');

        $data = '<field type="Email" ';

        foreach($test['attribs'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= ' />';

        $xml = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        foreach($test['properties'] as $key => $value)
        {
            $field->$key = $value;
        }

        $html = $field->getFieldContents($test['options']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }
}
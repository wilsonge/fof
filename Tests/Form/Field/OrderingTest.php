<?php

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Ordering;
use FOF30\Form\Form;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once __DIR__.'/OrderingDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Ordering::<private>
 * @covers  FOF30\Form\Field\Ordering::<protected>
 */
class OrderingTest extends DatabaseTest
{
    /**
     * @group           OrderingField
     * @group           Ordering__get
     * @covers          FOF30\Form\Field\Ordering::__get
     * @dataProvider    FOF30\Tests\Form\Field\OrderingDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Ordering', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           OrderingField
     * @group           OrderingGetInput
     * @covers          FOF30\Form\Field\Ordering::getInput
     * @dataProvider    FOF30\Tests\Form\Field\OrderingDataprovider::getTestGetInput
     */
    public function testGetInput($test, $check)
    {
        $msg = 'Ordering::getInput %s - Case: '.$check['case'];

        $field = new Ordering();

        foreach($test['properties'] as $key => $value)
        {
            ReflectionHelper::setValue($field, $key, $value);
        }

        $data = '<field type="Ordering" ';

        foreach($test['attributes'] as $key => $value)
        {
            $data .= $key.'="'.$value.'" ';
        }

        $data .= '/>';
        $xml   = simplexml_load_string($data);
        ReflectionHelper::setValue($field, 'element', $xml);

        $config = array(
            'tableName' => '#__foftest_foobars',
            'idFieldName' => 'foftest_foobar_id'
        );

        $model = new DataModelStub(static::$container, $config);
        $form  = new Form(static::$container, 'Foobar');

        $model->find(1);

        $form->setModel($model);
        $field->setForm($form);

        $result = ReflectionHelper::invoke($field, 'getInput');

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}
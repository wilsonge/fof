<?php

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Model;
use FOF30\Form\Form;
use FOF30\Tests\Helpers\ClosureHelper;
use FOF30\Tests\Helpers\DatabaseTest;
use FOF30\Tests\Helpers\ReflectionHelper;
use FOF30\Tests\Stubs\Model\DataModelStub;

require_once __DIR__.'/ModelDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Model::<private>
 * @covers  FOF30\Form\Field\Model::<protected>
 */
class ModelTest extends DatabaseTest
{
    /**
     * @group           ModelField
     * @group           Model__get
     * @covers          FOF30\Form\Field\Model::__get
     * @dataProvider    FOF30\Tests\Form\Field\ModelDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Model', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           ModelField
     * @group           ModelGetRepeatable
     * @covers          FOF30\Form\Field\Model::getRepeatable
     * @dataProvider    FOF30\Tests\Form\Field\ModelDataprovider::getTestGetRepeatable
     */
    /*public function testGetRepeatable($test, $check)
    {
        $msg = 'Model::getRepeatable %s - Case: '.$check['case'];

        $field = new Model();

        $result = $field->getRepeatable();

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }*/

    /**
     * @group           ModelField
     * @group           ModelParseFieldTags
     * @covers          FOF30\Form\Field\Model::parseFieldTags
     * @dataProvider    FOF30\Tests\Form\Field\ModelDataprovider::getTestParseFieldTags
     */
    public function testParseFieldTags($test, $check)
    {
        $msg = 'Model::parseFieldTags %s - Case: '.$check['case'];

        $item   = null;
        $config = array(
            'idFieldName' => 'foftest_foobar_id',
            'tableName'   => '#__foftest_foobars'
        );

        $fakeSession = new ClosureHelper(array(
            'getFormToken' => function(){
                return '_FAKE_SESSION_';
            }
        ));

        \JFactory::$session = $fakeSession;

        $input = static::$container->input;
        $input->set('Itemid', 100);

        $model = new DataModelStub(static::$container, $config);
        $form  = new Form(static::$container, 'Foobar');
        $field = new Model();

        if($test['load'])
        {
            $model->find($test['load']);
        }

        if($test['assign'])
        {
            $item = $model;
        }

        $form->setModel($model);
        $field->setForm($form);

        $result = ReflectionHelper::invoke($field, 'parseFieldTags', $test['text'], $item);

        $this->assertEquals($check['result'], $result, sprintf($msg, 'Returned the wrong result'));
    }
}
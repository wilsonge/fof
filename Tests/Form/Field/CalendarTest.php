<?php

namespace FOF30\Tests\Form\Field;

use FOF30\Form\Field\Calendar;
use FOF30\Form\Form;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once __DIR__.'/CalendarDataprovider.php';

/**
 * @covers  FOF30\Form\Field\Calendar::<private>
 * @covers  FOF30\Form\Field\Calendar::<protected>
 */
class CalendarTest extends FOFTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->saveFactoryState();

        \JFactory::$application = $this->getMockCmsApp();
    }

    protected function tearDown()
    {
        $this->restoreFactoryState();

        parent::tearDown();
    }

    /**
     * @group           Calendar
     * @group           Calendar__get
     * @covers          FOF30\Form\Field\Calendar::__get
     * @dataProvider    CalendarDataprovider::getTest__get
     */
    public function test__get($test, $check)
    {
        $field = $this->getMock('FOF30\Form\Field\Calendar', array('getStatic', 'getRepeatable'));
        $field->expects($this->exactly($check['static']))->method('getStatic');
        $field->expects($this->exactly($check['repeat']))->method('getRepeatable');

        ReflectionHelper::setValue($field, 'static', $test['static']);
        ReflectionHelper::setValue($field, 'repeatable', $test['repeat']);

        $property = $test['property'];

        $field->$property;
    }

    /**
     * @group           Calendar
     * @group           CalendarGetStatic
     * @covers          FOF30\Form\Field\Calendar::getStatic
     */
    public function testGetStatic()
    {
        $field = $this->getMock('FOF30\Form\Field\Calendar', array('getCalendar'));
        $field->expects($this->once())->method('getCalendar')->with($this->equalTo('static'));

        $field->getStatic();
    }

    /**
     * @group           Calendar
     * @group           CalendarGetRepeatable
     * @covers          FOF30\Form\Field\Calendar::getRepeatable
     */
    public function testGetRepeatable()
    {
        $field = $this->getMock('FOF30\Form\Field\Calendar', array('getCalendar'));
        $field->expects($this->once())->method('getCalendar')->with($this->equalTo('repeatable'));

        $field->getRepeatable();
    }

    /**
     * @group           Calendar
     * @group           CalendarGetCalendar
     * @covers          FOF30\Form\Field\Calendar::getCalendar
     * @dataProvider    CalendarDataprovider::getTestGetCalendar
     */
    /*public function testGetCalendar($test, $check)
    {
        $msg = 'Calendar::getCalendar %s - Case: '.$check['case'];

        $form = new Form(static::$container, 'Foobar');

        $field = new Calendar();
        ReflectionHelper::setValue($field, 'form', $form);

        $field->class = $test['class'];
        $field->value = $test['value'];
        $field->filter = $test['filter'];
        $field->size = $test['size'];
        $field->maxlength = $test['maxlength'];
        $field->readonly = $test['readonly'];
        $field->disabled = $test['disabled'];
        $field->required = $test['required'];
        $field->name = 'foobar-name';
        $field->id = 'foobar-id';

        $html = ReflectionHelper::invoke($field, 'getCalendar', $test['display']);

        $this->assertEquals($check['result'], $html, sprintf($msg, 'Returned the wrong result'));
    }*/
}
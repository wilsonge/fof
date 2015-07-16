<?php
/**
 * @package        FOF
 * @copyright      2014 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license        GNU GPL version 3 or later
 */

namespace FOF30\Tests\Form;

use FOF30\Form\Form;
use FOF30\Tests\Helpers\FOFTestCase;
use FOF30\Tests\Helpers\ReflectionHelper;

require_once 'FormDataprovider.php';

/**
 * @covers      FOF30\Form\Form::<protected>
 * @covers      FOF30\Form\Form::<private>
 * @package     FOF30\Tests\Form
 */
class FormTest extends FOFTestCase
{
    /**
     * @group           Form
     * @group           Form__construct
     * @covers          FOF30\Form\Form::__construct
     */
    public function test__construct()
    {
        $form = new Form(static::$container, 'Foobar');

        $this->assertSame(static::$container, ReflectionHelper::getValue($form, 'container'), 'Failed to pass the container to the form');
    }

    /**
     * @group           Form
     * @group           FormGetAttribute
     * @covers          FOF30\Form\Form::getAttribute
     * @dataProvider    FormDataprovider::getTestGetAttribute
     */
    public function testGetAttribute($test, $check)
    {
        $msg = 'Form::getAttribute %s - Case: '.$check['case'];

        $form = new Form(static::$container, 'Foobar');
        $form->loadFile(JPATH_TESTS.'/_data/form/form.default.xml');

        $attribute = $form->getAttribute($test['attribute'], 'default');

        $this->assertEquals($check['result'], $attribute, sprintf($msg, 'Returned the wrong value'));
    }
}
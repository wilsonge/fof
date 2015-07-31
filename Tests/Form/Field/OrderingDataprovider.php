<?php

namespace FOF30\Tests\Form\Field;

class OrderingDataprovider
{
    public static function getTest__get()
    {
        $data[] = array(
            'input' => array(
                'property' => 'static',
                'static'   => null,
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the static method, not cached',
                'static' => 1,
                'repeat' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'static',
                'static'   => 'cached',
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the static method, cached',
                'static' => 0,
                'repeat' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'repeatable',
                'static'   => null,
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the repeatable method, not cached',
                'static' => 0,
                'repeat' => 1
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'repeatable',
                'static'   => null,
                'repeat'   => 'cached'
            ),
            'check' => array(
                'case'   => 'Requesting for the repeatable method, cached',
                'static' => 0,
                'repeat' => 0
            )
        );

        return $data;
    }

    public static function getTestGetInput()
    {
        $data[] = array(
            'input' => array(
                'properties' => array(
                    'class'     => 'foo-class',
                    'disabled'  => true,
                    'name'      => 'ordering',
                    'size'      => 5,
                    'onchange'  => '__ONCHANGE__',
                    'value'     => 5
                ),
                'attributes' => array()
            ),
            'check' => array(
                'case'   => 'Normal ordering field',
                'result' => '<select id="ordering" name="ordering" class="foo-class" disabled size="5" onchange="__ONCHANGE__">
	<option value="0">0 JOPTION_ORDER_FIRST</option>
	<option value="1">1. Guinea Pig row</option>
	<option value="2">2. Second row</option>
	<option value="3">3. Third row</option>
	<option value="4">4. Fourth row</option>
	<option value="5" selected="selected">5. Locked record</option>
	<option value="6">6 JOPTION_ORDER_LAST</option>
</select>
'
            )
        );

        $data[] = array(
            'input' => array(
                'properties' => array(
                    'class'     => 'foo-class',
                    'disabled'  => true,
                    'name'      => 'ordering',
                    'size'      => 5,
                    'onchange'  => '__ONCHANGE__',
                    'value'     => 5,
                    'readonly'  => true
                ),
                'attributes' => array()
            ),
            'check' => array(
                'case'   => 'Field is read only',
                'result' => '<select name="" class="foo-class" disabled size="5" onchange="__ONCHANGE__">
	<option value="0">0 JOPTION_ORDER_FIRST</option>
	<option value="1">1. Guinea Pig row</option>
	<option value="2">2. Second row</option>
	<option value="3">3. Third row</option>
	<option value="4">4. Fourth row</option>
	<option value="5" selected="selected">5. Locked record</option>
	<option value="6">6 JOPTION_ORDER_LAST</option>
</select>
<input type="hidden" name="ordering" value="5"/>'
            )
        );

        return $data;
    }
}
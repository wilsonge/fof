<?php

namespace FOF30\Tests\Form\Field;

class ModelDataprovider
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

    public static function getTestGetRepeatable()
    {
        $data[] = array(
            'input' => array(

            ),
            'check' => array(

            )
        );

        return $data;
    }

    public static function getTestParseFieldTags()
    {
        $data[] = array(
            'input' => array(
                'load'   => false,
                'assign' => false,
                'text'   => '__ID:[ITEM:ID]__ __ITEMID:[ITEMID]__ __TOKEN:[TOKEN]__ __TITLE:[ITEM:TITLE]__'
            ),
            'check' => array(
                'case' => 'Record no assigned and not loaded',
                'result' => '__ID:__ __ITEMID:100__ __TOKEN:_FAKE_SESSION___ __TITLE:__'
            )
        );

        $data[] = array(
            'input' => array(
                'load'   => 1,
                'assign' => false,
                'text'   => '__ID:[ITEM:ID]__ __ITEMID:[ITEMID]__ __TOKEN:[TOKEN]__ __TITLE:[ITEM:TITLE]__'
            ),
            'check' => array(
                'case' => 'Record no assigned and loaded',
                'result' => '__ID:1__ __ITEMID:100__ __TOKEN:_FAKE_SESSION___ __TITLE:Guinea Pig row__'
            )
        );

        $data[] = array(
            'input' => array(
                'load'   => 1,
                'assign' => true,
                'text'   => '__WRONG:[ITEM:WRONG]__'
            ),
            'check' => array(
                'case' => 'Record assigned and loaded, field not existing',
                'result' => '__WRONG:[ITEM:WRONG]__'
            )
        );

        return $data;
    }
}

<?php

class PublishedDataprovider
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

    public static function getTestGetStatic()
    {
        $data[] = array(
            'input' => array(
                'value' => ''
            ),
            'check' => array(
                'case'   => 'No value set',
                'result' => '<span id="foo_id"  class="foo-class">Unpublished</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'value' => 1
            ),
            'check' => array(
                'case'   => 'Value set',
                'result' => '<span id="foo_id"  class="foo-class">Published</span>'
            )
        );

        return $data;
    }
}

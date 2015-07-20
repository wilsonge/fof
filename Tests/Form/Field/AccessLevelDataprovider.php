<?php

class AccessLevelDataprovider
{
    public static function getTestGetFieldContents()
    {
        $data[] = array(
            'input' => array(
                'mock' => array(
                    'options' => array()
                ),
                'value'   => '2',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'No default options or field options',
                'result' => '<span class="">Registered</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'options' => array()
                ),
                'value'   => '2',
                'options' => array(
                    'id' => 'foobar',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'   => 'With field options',
                'result' => '<span id="foobar" class=" foo-class">Registered</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'options' => true
                ),
                'value'   => '',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'With "All" option',
                'result' => '<span class="">JOPTION_ACCESS_SHOW_ALL_LEVELS</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'mock' => array(
                    'options' => false
                ),
                'value'   => '2',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'With "All" option',
                'result' => '<span class="">Registered</span>'
            )
        );

        return $data;
    }
}

<?php

class FormDataprovider
{
    public static function getTestGetAttribute()
    {
        $data[] = array(
            'input' => array(
                'attribute' => 'type'
            ),
            'check' => array(
                'case' => 'Existing attribute',
                'result' => 'browse'
            )
        );

        $data[] = array(
            'input' => array(
                'attribute' => 'iamnothere'
            ),
            'check' => array(
                'case' => 'Non existing attribute',
                'result' => 'default'
            )
        );

        return $data;
    }
}
<?php

class TagsDataprovider
{
    public static function getTestOnAfterBind()
    {
        $data[] = array(
            'input' => array(
                'load' => 0,
                'tags' => ''
            ),
            'check' => array(
                'case' => '',
                'contentType' => '',
                'addKnown' => '',
                'result' => ''
            )
        );

        return $data;
    }
}
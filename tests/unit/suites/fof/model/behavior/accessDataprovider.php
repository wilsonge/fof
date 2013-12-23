<?php

class accessDataprovider
{
    public static function getTestOnAfterBuildQuery()
    {
        $data[] = array(
            array('name' => 'foobars'),
            array('frontend' => false),
            array('execute' => false)
        );

        $data[] = array(
            array('name' => 'foobars'),
            array('frontend' => true),
            array('execute' => true)
        );

        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'frontend' => true,
                'aliases'  => array(
                    'access' => 'fo_access'
                )
            ),
            array('execute' => true)
        );

        $data[] = array(
            array('name' => 'bares'),
            array(
                'frontend' => true
            ),
            array('execute' => false)
        );

        return $data;
    }
}
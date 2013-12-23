<?php

class accessDataprovider
{
    public static function getTestOnAfterBuildQuery()
    {
        $data[] = array(
            array('name' => 'foobars'),
            array('frontend' => false)
        );

        $data[] = array(
            array('name' => 'foobars'),
            array('frontend' => true)
        );

        return $data;
    }
}
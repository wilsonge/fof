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

    public static function getTestOnAfterGetItem()
    {
        // User has access
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'loadid' => 1,
                'views'  => array(1, 2)
            ),
            array('nullify' => false)
        );

        // User has NO access
        $data[] = array(
            array('name' => 'foobars'),
            array(
                'loadid' => 1,
                'views'  => array(1)
            ),
            array('nullify' => true)
        );

        // User has access - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'loadid' => 1,
                'views'  => array(1, 2),
                'aliases' => array(
                    'tbl_key' => 'id_foobar_aliases',
                    'access'  => 'fo_access'
                )
            ),
            array('nullify' => false)
        );

        // User has NO access - table alias
        $data[] = array(
            array('name' => 'foobaraliases'),
            array(
                'loadid' => 1,
                'views'  => array(1),
                'aliases' => array(
                    'tbl_key' => 'id_foobar_aliases',
                    'access'  => 'fo_access'
                )
            ),
            array('nullify' => true)
        );

        // Table with no access support
        $data[] = array(
            array('name' => 'bares'),
            array(
                'loadid' => 1,
                'views'  => array()
            ),
            array('nullify' => false)
        );

        return $data;
    }
}
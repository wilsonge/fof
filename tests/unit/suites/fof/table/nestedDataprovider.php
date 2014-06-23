<?php

class NestedDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'table' => '#__foftest_nestedsets',
                'id'    => 'id'
            ),
            array(
                'exception' => false
            )
        );

        $data[] = array(
            array(
                'table' => '#__foftest_bares',
                'id'    => 'foftest_bare_id'
            ),
            array(
                'exception' => true
            )
        );

        return $data;
    }

    public static function getTestCheck()
    {
        $data[] = array(
            array(
                'table' => '#__foftest_nestedsets',
                'id'    => 'id',
                'fields' => array(
                    'title' => 'Test title',
                    'slug'  => ''
                )
            ),
            array(
                'fields' => array(
                    'slug'   => 'test-title',
                    'hash'   => sha1('test-title')
                ),
                'return' => true
            )
        );

        $data[] = array(
            array(
                'table' => '#__foftest_nestedsets',
                'id'    => 'id',
                'fields' => array(
                    'title' => 'Test title',
                    'slug'  => 'old-slug'
                )
            ),
            array(
                'fields' => array(
                    'slug'   => 'old-slug',
                    'hash'   => sha1('old-slug')
                ),
                'return' => true
            )
        );

        $data[] = array(
            array(
                'table' => '#__foftest_nestedbares',
                'id'    => 'id',
                'fields' => array()
            ),
            array(
                'fields' => array(
                    'slug' => null,
                    'hash' => null
                ),
                'return' => true
            )
        );

        return $data;
    }
}
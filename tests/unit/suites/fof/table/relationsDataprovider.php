<?php

abstract class RelationsDataprovider
{
    public static function getTest__construct()
    {
        // Child table with parents
        $data[] = array(
            array('table' => 'child'),
            '',
            array(
                'hasParent' => true,
                'relation'  => array(
                    'key'     => 'parent',
                    'content' => array(
                        'tableClass'  => 'FoftestTableParent',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                ),
                'default'   => array(
                    'parent'  => 'parent'
                )
            )
        );

        // Table with no parent link
        $data[] = array(
            array('table' => 'bare'),
            '',
            array(
                'hasParent' => false,
                'relation'  => array(
                    'key'     => 'parent'
                )
            )
        );

        return $data;
    }
}
 
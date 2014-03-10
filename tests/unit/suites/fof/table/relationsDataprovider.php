<?php

abstract class RelationsDataprovider
{
    public static function getTest__construct()
    {
        // Child table with parents
        $data[] = array(
            array('table' => 'child'),
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
            array(
                'hasParent' => false,
                'relation'  => array(
                    'key'     => 'parent'
                )
            )
        );

        return $data;
    }

    public static function getTestAddChildRelation()
    {
        // Default usage of child relation, everything is "magically" set
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'itemName'   => 'child',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => true
                )
            ),
            array(
                'relation' => array(
                    'default'   => 'child',
                    'key'       => 'child',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableChild',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                )
            )
        );

        // Relation is not the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'itemName'   => 'child',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'child',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableChild',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                )
            )
        );

        // Force relation parameters
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'itemName'   => 'child',
                    'tableClass' => 'FoftestTableTestchild',
                    'localKey'   => 'foftest_local_id',
                    'remoteKey'  => 'foftest_remote_id',
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'child',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableTestchild',
                        'localKey'    => 'foftest_local_id',
                        'remoteKey'   => 'foftest_remote_id',
                    )
                )
            )
        );

        return $data;
    }

    public static function getTestAddParentRelation()
    {
        // Default usage of child relation, everything is "magically" set
        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array(
                    'itemName'   => 'parent',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => true
                )
            ),
            array(
                'relation' => array(
                    'default'   => 'parent',
                    'key'       => 'parent',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableParent',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                )
            )
        );

        // Relation is not the default one
        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array(
                    'itemName'   => 'parent',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'parent',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableParent',
                        'localKey'    => 'foftest_parent_id',
                        'remoteKey'   => 'foftest_parent_id',
                    )
                )
            )
        );

        // Force relation parameters
        $data[] = array(
            array('table' => 'child'),
            array(
                'relation' => array(
                    'itemName'   => 'parent',
                    'tableClass' => 'FoftestTableTestparent',
                    'localKey'   => 'foftest_local_id',
                    'remoteKey'  => 'foftest_remote_id',
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'parent',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableTestparent',
                        'localKey'    => 'foftest_local_id',
                        'remoteKey'   => 'foftest_remote_id',
                    )
                )
            )
        );

        return $data;
    }
}
 
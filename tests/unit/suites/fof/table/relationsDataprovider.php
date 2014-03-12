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

    public static function getTestAddChildrenRelation()
    {
        // Default usage of children relation, everything is "magically" set
        $data[] = array(
            array('table' => 'parent'),
            array(
                'relation' => array(
                    'itemName'   => 'children',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => true
                )
            ),
            array(
                'relation' => array(
                    'default'   => 'children',
                    'key'       => 'children',
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
                    'itemName'   => 'children',
                    'tableClass' => null,
                    'localKey'   => null,
                    'remoteKey'  => null,
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'children',
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
                    'itemName'   => 'children',
                    'tableClass' => 'FoftestTableTestchildren',
                    'localKey'   => 'foftest_local_id',
                    'remoteKey'  => 'foftest_remote_id',
                    'default'    => false
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'children',
                    'content'   => array(
                        'tableClass'  => 'FoftestTableTestchildren',
                        'localKey'    => 'foftest_local_id',
                        'remoteKey'   => 'foftest_remote_id',
                    )
                )
            )
        );

        return $data;
    }

    public static function getTestAddMultipleRelation()
    {
        // Default usage of multiple relation, everything is "magically" set - relation name is singular
        $data[] = array(
            array('table' => 'part'),
            array(
                'relation' => array(
                    'itemName'      => 'group',
                    'tableClass'    => null,
                    'localKey'      => null,
                    'ourPivot'      => null,
                    'theirPivot'    => null,
                    'remoteKey'     => null,
                    'glueTable'     => null,
                    'default'       => true,
                )
            ),
            array(
                'relation' => array(
                    'default'   => 'groups',
                    'key'       => 'groups',
                    'content'   => array(
                        'tableClass'    => 'FoftestTableGroup',
                        'localKey'      => 'foftest_part_id',
                        'ourPivotKey'   => 'foftest_part_id',
                        'theirPivotKey' => 'foftest_part_id',
                        'remoteKey'     => 'foftest_part_id',
                        'pivotTable'    => '#__foftest_parts_groups',
                    )
                )

            )
        );

        // Default usage of multiple relation, everything is "magically" set - relation name is plural
        $data[] = array(
            array('table' => 'part'),
            array(
                'relation' => array(
                    'itemName'      => 'groups',
                    'tableClass'    => null,
                    'localKey'      => null,
                    'ourPivot'      => null,
                    'theirPivot'    => null,
                    'remoteKey'     => null,
                    'glueTable'     => null,
                    'default'       => true,
                )
            ),
            array(
                'relation' => array(
                    'default'   => 'groups',
                    'key'       => 'groups',
                    'content'   => array(
                        'tableClass'    => 'FoftestTableGroup',
                        'localKey'      => 'foftest_part_id',
                        'ourPivotKey'   => 'foftest_part_id',
                        'theirPivotKey' => 'foftest_part_id',
                        'remoteKey'     => 'foftest_part_id',
                        'pivotTable'    => '#__foftest_parts_groups',
                    )
                )

            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'relation' => array(
                    'itemName'      => 'group',
                    'tableClass'    => null,
                    'localKey'      => null,
                    'ourPivot'      => null,
                    'theirPivot'    => null,
                    'remoteKey'     => null,
                    'glueTable'     => null,
                    'default'       => false,
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'groups',
                    'content'   => array(
                        'tableClass'    => 'FoftestTableGroup',
                        'localKey'      => 'foftest_part_id',
                        'ourPivotKey'   => 'foftest_part_id',
                        'theirPivotKey' => 'foftest_part_id',
                        'remoteKey'     => 'foftest_part_id',
                        'pivotTable'    => '#__foftest_parts_groups',
                    )
                )

            )
        );

        $data[] = array(
            array('table' => 'part'),
            array(
                'relation' => array(
                    'itemName'      => 'group',
                    'tableClass'    => 'FoftestTableTestgroup',
                    'localKey'      => 'foftest_local_id',
                    'ourPivot'      => 'foftest_ourpivot_id',
                    'theirPivot'    => 'foftest_theirpivot_id',
                    'remoteKey'     => 'foftest_remote_id',
                    'glueTable'     => '#__foftest_gluetable',
                    'default'       => false,
                )
            ),
            array(
                'relation' => array(
                    'default'   => null,
                    'key'       => 'groups',
                    'content'   => array(
                        'tableClass'    => 'FoftestTableTestgroup',
                        'localKey'      => 'foftest_local_id',
                        'ourPivotKey'   => 'foftest_ourpivot_id',
                        'theirPivotKey' => 'foftest_theirpivot_id',
                        'remoteKey'     => 'foftest_remote_id',
                        'pivotTable'    => '#__foftest_gluetable',
                    )
                )

            )
        );

        return $data;
    }

    public static function getTestRemoveRelation()
    {
        // Try to remove only one relation, without supplying the type
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Try to remove only one relation, supplying the type
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => 'parent',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Try to remove only one relation, supplying the (wrong) type
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => 'child',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Try to remove only one relation, without supplying the type, but it's marked as the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'foftest_parent',
                    'children'  => null,
                    'multiple'  => null
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            )
        );

        // Try to remove only one relation, without supplying the type, but it's NOT marked as the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'itemName'  => 'foftest_parent',
                'type'      => null,
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'another_relation',
                    'children'  => null,
                    'multiple'  => null
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'another_relation',
                    'children'  => null,
                    'multiple'  => null,
                )
            )
        );


        return $data;
    }

    public static function getTestClearRelations()
    {
        // Try to remove relations only from the parent group
        $data[] = array(
            array('table' => 'parent'),
            array(
                'type'      => 'parent',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Try to remove relations only from the "wrong" type
        $data[] = array(
            array('table' => 'parent'),
            array(
                'type'      => 'children',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                )
            )
        );

        // Remove a relations that is the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'type'      => 'parent',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'foftest_parent',
                    'children'  => null,
                    'multiple'  => null
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => null,
                    'children'  => null,
                    'multiple'  => null,
                )
            )
        );

        // Remove a relations that is NOT the default one
        $data[] = array(
            array('table' => 'parent'),
            array(
                'type'      => 'children',
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array()  // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'foftest_parent',
                    'children'  => null,
                    'multiple'  => null
                )
            ),
            array(
                'relations' => array(
                    'child'     => array(
                        'foftest_child' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'parent'    => array(
                        'foftest_parent' => array() // I can simply ignore the contents, since they're not used
                    ),
                    'children'  => array(),
                    'multiple'  => array(),
                ),
                'default'   => array(
                    'child'     => null,
                    'parent'    => 'foftest_parent',
                    'children'  => null,
                    'multiple'  => null,
                )
            )
        );

        return $data;
    }
}
 
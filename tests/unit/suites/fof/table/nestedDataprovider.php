<?php

class NestedDataprovider
{
    public static function getTest__construct()
    {
        $data[] = array(
            array(
                'table' => '#__foftest_nestedsets',
                'id'    => 'foftest_nestedset_id'
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
                'id'    => 'foftest_nestedset_id',
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
                'id'    => 'foftest_nestedset_id',
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

    public static function getTestDelete()
    {
        // Delete a single leaf item (with recursive - useless, but to test everything)
        $data[] = array(
            array(
                'loadid'    => null,
                'delete'    => 15,
                'recursive' => true
            ),
            array(
                'return'  => true,
                'deleted' => array(15)
            )
        );

        // Delete a single leaf item (no recursive - useless, but to test everything)
        $data[] = array(
            array(
                'loadid'    => null,
                'delete'    => 15,
                'recursive' => false
            ),
            array(
                'return'  => true,
                'deleted' => array(15)
            )
        );

        // Delete a single root item (no recursive delete)
        $data[] = array(
            array(
                'loadid'    => null,
                'delete'    => 14,
                'recursive' => false
            ),
            array(
                'return'  => true,
                'deleted' => array(14)
            )
        );

        // Delete a single root item (recursive delete)
        $data[] = array(
            array(
                'loadid'    => null,
                'delete'    => 14,
                'recursive' => true
            ),
            array(
                'return'  => true,
                'deleted' => array(14, 15, 16)
            )
        );

        return $data;
    }

    public static function getTestCreate()
    {
        // Create a node under the root
        $data[] = array(
            array(
                'root'   => true,
                'loadid' => 1,
                'data'   => array(
                    'title' => 'Created node'
                )
            )
        );

        // Create a node in any other position
        $data[] = array(
            array(
                'root'   => false,
                'loadid' => 2,
                'data'   => array(
                    'title' => 'Created node'
                )
            )
        );

        return $data;
    }

    public static function getTestInsertAsFirstChildOf()
    {
        // Creating a new node
        $data[] = array(
            array(
                'loadid'   => 0,
                'parentid' => 14,
                'title'    => 'First child'
            )
        );

        // Copying an existing node of the same parent (it's not the first child)
        $data[] = array(
            array(
                'loadid'   => 16,
                'parentid' => 14,
                'title'    => ''
            )
        );

        // Copying an existing node of the same parent (it's the first child)
        $data[] = array(
            array(
                'loadid'   => 15,
                'parentid' => 14,
                'title'    => ''
            )
        );

        // Copying an existing node of another parent
        $data[] = array(
            array(
                'loadid'   => 4,
                'parentid' => 14,
                'title'    => ''
            )
        );

        return $data;
    }

    public static function getTestMakeRoot()
    {
        $data[] = array(
            array(
                'setup' => array(
                    array('foftest_nestedset_id' => 1, 'title' => 'Original root', 'lft' => 1, 'rgt' => 4),
                    array('foftest_nestedset_id' => 2, 'title' => 'Child', 'lft' => 2, 'rgt' => 3)
                ),
                'loadid' => 2
            )
        );

        $data[] = array(
            array(
                'setup'  => array(),
                'loadid' => 9
            )
        );

        return $data;
    }
}
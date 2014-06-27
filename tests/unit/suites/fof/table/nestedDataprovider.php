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

    public static function getTestInsertAsLastChildOf()
    {
        // Creating a new node
        $data[] = array(
            array(
                'loadid'   => 0,
                'parentid' => 14,
                'title'    => 'Last child'
            )
        );

        // Copying an existing node of the same parent (it's not the last child)
        $data[] = array(
            array(
                'loadid'   => 15,
                'parentid' => 14,
                'title'    => ''
            )
        );

        // Copying an existing node of the same parent (it's the last child)
        $data[] = array(
            array(
                'loadid'   => 16,
                'parentid' => 14,
                'title'    => ''
            )
        );

        // Copying an existing node with children
        $data[] = array(
            array(
                'loadid'   => 10,
                'parentid' => 9,
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

    public static function getTestInsertLeftOf()
    {
        // Creating a new node
        $data[] = array(
            array(
                'loadid' => 0,
                'siblingid' => 13,
                'title' => 'Left sibling'
            )
        );

        // Copying an existing node
        $data[] = array(
            array(
                'loadid' => 10,
                'siblingid' => 13,
                'title' => ''
            )
        );

        return $data;
    }

    public static function getTestInsertRightOf()
    {
        // Creating a new node
        $data[] = array(
            array(
                'loadid' => 0,
                'siblingid' => 13,
                'title' => 'Right sibling'
            )
        );

        // Copying an existing node
        $data[] = array(
            array(
                'loadid' => 10,
                'siblingid' => 13,
                'title' => ''
            )
        );

        return $data;
    }

    public static function getTestMoveLeft()
    {
        // Node in the middle of another two
        $data[] = array(
            array(
                'loadid' => 13
            ),
            array(
                'move'        => true,
                'leftSibling' => 10
            )
        );

        // Root node
        $data[] = array(
            array(
                'loadid' => 1
            ),
            array(
                'move'        => false,
                'leftSibling' => ''
            )
        );

        // Already a leftmost node
        $data[] = array(
            array(
                'loadid' => 10
            ),
            array(
                'move'        => false,
                'leftSibling' => ''
            )
        );

        return $data;
    }

    public static function getTestMoveRight()
    {
        // Node in the middle of another two
        $data[] = array(
            array(
                'loadid' => 13
            ),
            array(
                'move'        => true,
                'rightSibling' => 14
            )
        );

        // Root node
        $data[] = array(
            array(
                'loadid' => 1
            ),
            array(
                'move'        => false,
                'rightSibling' => ''
            )
        );

        // Already a rightmost node
        $data[] = array(
            array(
                'loadid' => 14
            ),
            array(
                'move'        => false,
                'rightSibling' => ''
            )
        );

        return $data;
    }

    public static function getTestMoveToLeftOf()
    {
        // Moving a node to the left
        $data[] = array(
            array(
                'newRoot' => false,
                'loadid' => 13,
                'siblingid' => 10
            ),
            array(
                'table'   => array('lft' => 17, 'rgt' => 18),
                'sibling' => array('lft' => 19, 'rgt' => 24)
            )
        );

        // Trying to move the leftmost node to the left (no changes at all)
        $data[] = array(
            array(
                'newRoot' => false,
                'loadid' => 10,
                'siblingid' => 13
            ),
            array(
                'table'   => array('lft' => 17, 'rgt' => 22),
                'sibling' => array('lft' => 23, 'rgt' => 24)
            )
        );

        // There are more roots, let's try to move one
        $data[] = array(
            array(
                'newRoot' => true,
                'loadid' => 17,
                'siblingid' => 1
            ),
            array(
                'table'   => array('lft' => 1, 'rgt' => 6),
                'sibling' => array('lft' => 7, 'rgt' => 38)
            )
        );

        return $data;
    }

    public static function getTestMoveToLeftOfException()
    {
        $data[] = array(
            'loadid'    => 0,
            'siblingid' => 0
        );

        $data[] = array(
            'loadid'    => 1,
            'siblingid' => 0
        );

        $data[] = array(
            'loadid'    => 0,
            'siblingid' => 1
        );

        return $data;
    }

    public static function getTestMoveToRightOf()
    {
        // Moving a node to the left
        $data[] = array(
            array(
                'newRoot' => false,
                'loadid' => 10,
                'siblingid' => 13
            ),
            array(
                'table'   => array('lft' => 19, 'rgt' => 24),
                'sibling' => array('lft' => 17, 'rgt' => 18)
            )
        );

        // Trying to move the rightmost node to the right (no changes at all)
        $data[] = array(
            array(
                'newRoot' => false,
                'loadid' => 14,
                'siblingid' => 13
            ),
            array(
                'table'   => array('lft' => 25, 'rgt' => 30),
                'sibling' => array('lft' => 23, 'rgt' => 24)
            )
        );

        // There are more roots, let's try to move one
        $data[] = array(
            array(
                'newRoot' => true,
                'loadid' => 1,
                'siblingid' => 17
            ),
            array(
                'table'   => array('lft' => 7, 'rgt' => 38),
                'sibling' => array('lft' => 1, 'rgt' => 6)
            )
        );

        return $data;
    }

    public static function getTestMoveToRightOfException()
    {
        $data[] = array(
            'loadid'    => 0,
            'siblingid' => 0
        );

        $data[] = array(
            'loadid'    => 1,
            'siblingid' => 0
        );

        $data[] = array(
            'loadid'    => 0,
            'siblingid' => 1
        );

        return $data;
    }

    public static function getTestMakeFirstChildOf()
    {
        // Moving a single node
        $data[] = array(
            array(
                'loadid'   => 13,
                'parentid' => 2
            ),
            array(
                'table'  => array('lft' => 3, 'rgt' => 4),
                'parent' => array('lft' => 2, 'rgt' => 17)
            )
        );

        // Moving an entire subtree
        $data[] = array(
            array(
                'loadid'   => 10,
                'parentid' => 2
            ),
            array(
                'table'  => array('lft' => 3, 'rgt' => 8),
                'parent' => array('lft' => 2, 'rgt' => 21)
            )
        );

        // Moving a single node under the same parent
        $data[] = array(
            array(
                'loadid'   => 13,
                'parentid' => 9
            ),
            array(
                'table'  => array('lft' => 17, 'rgt' => 18),
                'parent' => array('lft' => 16, 'rgt' => 31)
            )
        );

        return $data;
    }

    public static function getTestMakeFirstChildOfException()
    {
        $data[] = array(
            'loadid'   => 0,
            'parentid' => 0
        );

        $data[] = array(
            'loadid'   => 1,
            'parentid' => 0
        );

        $data[] = array(
            'loadid'   => 0,
            'parentid' => 1
        );

        return $data;
    }

    public static function getTestMakeLastChildOf()
    {
        // Moving a single node
        $data[] = array(
            array(
                'loadid'   => 13,
                'parentid' => 2
            ),
            array(
                'table'  => array('lft' => 15, 'rgt' => 16),
                'parent' => array('lft' => 2, 'rgt' => 17)
            )
        );

        // Moving an entire subtree
        $data[] = array(
            array(
                'loadid'   => 10,
                'parentid' => 2
            ),
            array(
                'table'  => array('lft' => 15, 'rgt' => 20),
                'parent' => array('lft' => 2, 'rgt' => 21)
            )
        );

        // Moving a single node under the same parent
        $data[] = array(
            array(
                'loadid'   => 13,
                'parentid' => 9
            ),
            array(
                'table'  => array('lft' => 29, 'rgt' => 30),
                'parent' => array('lft' => 16, 'rgt' => 31)
            )
        );

        return $data;
    }

    public static function getTestMakeLastChildOfException()
    {
        $data[] = array(
            'loadid'   => 0,
            'parentid' => 0
        );

        $data[] = array(
            'loadid'   => 1,
            'parentid' => 0
        );

        $data[] = array(
            'loadid'   => 0,
            'parentid' => 1
        );

        return $data;
    }

    public static function getTestMakeRoot()
    {
        // Node is root
        $data[] = array(
            array(
                'mock' => array(
                    'isRoot' => true,
                    'equals' => true
                )
            ),
            array(
                'move' => false
            )
        );

        // Node is equal to the root
        $data[] = array(
            array(
                'mock' => array(
                    'isRoot' => false,
                    'equals' => true
                )
            ),
            array(
                'move' => false
            )
        );

        // Ok, we can move it
        $data[] = array(
            array(
                'mock' => array(
                    'isRoot' => false,
                    'equals' => false
                )
            ),
            array(
                'move' => true
            )
        );

        return $data;
    }

    public static function getTestGetLevel()
    {
        // Node
        $data[] = array(
            array(
                'cache' => null,
                'loadid' => 2
            ),
            array(
                'level' => 1
            )
        );

        // Root
        $data[] = array(
            array(
                'cache' => null,
                'loadid' => 1
            ),
            array(
                'level' => 0
            )
        );

        // Cached value
        $data[] = array(
            array(
                'cache' => 'cached',
                'loadid' => 1
            ),
            array(
                'level' => 'cached'
            )
        );

        return $data;
    }

    public static function getTestGetParent()
    {
        // Root node, we simply return ourself
        $data[] = array(
            array(
                'loadid' => 1,
                'cache'  => null,
            ),
            array(
                'parent' => 1
            )
        );

        // Child node
        $data[] = array(
            array(
                'loadid' => 16,
                'cache'  => null,
            ),
            array(
                'parent' => 14
            )
        );

        // Child node - wrong cache
        $data[] = array(
            array(
                'loadid' => 16,
                'cache'  => 'dummy',
            ),
            array(
                'parent' => 14
            )
        );

        // Child node - wrong cache 2
        $data[] = array(
            array(
                'loadid' => 16,
                'cache'  => new stdClass(),
            ),
            array(
                'parent' => 14
            )
        );

        // Child node - correct cache
        $data[] = array(
            array(
                'loadid' => 16,
                'cache'  => 'loadself',
            ),
            array(
                'parent' => 16
            )
        );

        return $data;
    }

    public static function getTestIsRoot()
    {
        // Root node
        $data[] = array(
            array(
                'loadid' => 1,
                'mock' => array(
                    'getLevel' => 0
                )

            ),
            array(
                'getLevel' => 0,
                'result'   => true
            )
        );

        // Child node
        $data[] = array(
            array(
                'loadid' => 2,
                'mock' => array(
                    'getLevel' => 1
                )

            ),
            array(
                'getLevel' => 1,
                'result'   => false
            )
        );

        // Second root
        $data[] = array(
            array(
                'loadid' => 2,
                'mock' => array(
                    'getLevel' => 0
                )

            ),
            array(
                'getLevel' => 1,
                'result'   => true
            )
        );

        return $data;
    }

    public static function getTestIsLeaf()
    {
        $data[] = array(
            array(
                'lft' => 10,
                'rgt' => 11
            ),
            array(
                'result' => true
            )
        );

        $data[] = array(
            array(
                'lft' => 10,
                'rgt' => 13
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestIsDescendantOf()
    {
        $data[] = array(
            array(
                'loadid'  => 10,
                'otherid' => 9
            ),
            array(
                'result' => true
            )
        );

        $data[] = array(
            array(
                'loadid'  => 3,
                'otherid' => 9
            ),
            array(
                'result' => false
            )
        );

        $data[] = array(
            array(
                'loadid'  => 9,
                'otherid' => 9
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestIsDescendantOfException()
    {
        $data[] = array(
            array(
                'loadid'  => 0,
                'otherid' => 0
            )
        );

        $data[] = array(
            array(
                'loadid'  => 1,
                'otherid' => 0
            )
        );

        $data[] = array(
            array(
                'loadid'  => 0,
                'otherid' => 1
            )
        );

        return $data;
    }

    public static function getTestIsSelfOrDescendantOf()
    {
        $data[] = array(
            array(
                'loadid'  => 10,
                'otherid' => 9
            ),
            array(
                'result' => true
            )
        );

        $data[] = array(
            array(
                'loadid'  => 3,
                'otherid' => 9
            ),
            array(
                'result' => false
            )
        );

        $data[] = array(
            array(
                'loadid'  => 9,
                'otherid' => 9
            ),
            array(
                'result' => true
            )
        );

        return $data;
    }

    public static function getTestIsSelfOrDescendantOfException()
    {
        $data[] = array(
            array(
                'loadid'  => 0,
                'otherid' => 0
            )
        );

        $data[] = array(
            array(
                'loadid'  => 1,
                'otherid' => 0
            )
        );

        $data[] = array(
            array(
                'loadid'  => 0,
                'otherid' => 1
            )
        );

        return $data;
    }

    public static function getTestEquals()
    {
        // The node is truly the same
        $data[] = array(
            array(
                'loadid'  => 4,
                'otherid' => 4,
                'forceTableId' => null,
                'forceOtherId' => null
            ),
            array(
                'result' => true
            )
        );

        // Id is different
        $data[] = array(
            array(
                'loadid'  => 4,
                'otherid' => 4,
                'forceTableId' => 3,
                'forceOtherId' => null
            ),
            array(
                'result' => false
            )
        );

        // Lft/rgt value are different
        $data[] = array(
            array(
                'loadid'  => 4,
                'otherid' => 12,
                'forceTableId' => 12,
                'forceOtherId' => null
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestEqualsException()
    {
        $data[] = array(
            array(
                'loadid'  => 0,
                'otherid' => 0
            )
        );

        $data[] = array(
            array(
                'loadid'  => 1,
                'otherid' => 0
            )
        );

        $data[] = array(
            array(
                'loadid'  => 0,
                'otherid' => 1
            )
        );

        return $data;
    }

    public static function getTestInSameScope()
    {
        $data[] = array(
            array(
                'mock' => array(
                    'table' => array(
                        'isLeaf'  => true,
                        'isRoot'  => false,
                        'isChild' => false
                    ),
                    'other' => array(
                        'isLeaf'  => true,
                        'isRoot'  => false,
                        'isChild' => false
                    )
                )
            ),
            array(
                'result' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'table' => array(
                        'isLeaf'  => true,
                        'isRoot'  => false,
                        'isChild' => false
                    ),
                    'other' => array(
                        'isLeaf'  => false,
                        'isRoot'  => true,
                        'isChild' => false
                    )
                )
            ),
            array(
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'table' => array(
                        'isLeaf'  => true,
                        'isRoot'  => false,
                        'isChild' => false
                    ),
                    'other' => array(
                        'isLeaf'  => false,
                        'isRoot'  => false,
                        'isChild' => true
                    )
                )
            ),
            array(
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'table' => array(
                        'isLeaf'  => false,
                        'isRoot'  => true,
                        'isChild' => false
                    ),
                    'other' => array(
                        'isLeaf'  => false,
                        'isRoot'  => true,
                        'isChild' => false
                    )
                )
            ),
            array(
                'result' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'table' => array(
                        'isLeaf'  => false,
                        'isRoot'  => true,
                        'isChild' => false
                    ),
                    'other' => array(
                        'isLeaf'  => true,
                        'isRoot'  => false,
                        'isChild' => false
                    )
                )
            ),
            array(
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'table' => array(
                        'isLeaf'  => false,
                        'isRoot'  => true,
                        'isChild' => false
                    ),
                    'other' => array(
                        'isLeaf'  => false,
                        'isRoot'  => false,
                        'isChild' => true
                    )
                )
            ),
            array(
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'table' => array(
                        'isLeaf'  => false,
                        'isRoot'  => false,
                        'isChild' => true
                    ),
                    'other' => array(
                        'isLeaf'  => false,
                        'isRoot'  => false,
                        'isChild' => true
                    )
                )
            ),
            array(
                'result' => true
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'table' => array(
                        'isLeaf'  => false,
                        'isRoot'  => false,
                        'isChild' => true
                    ),
                    'other' => array(
                        'isLeaf'  => true,
                        'isRoot'  => false,
                        'isChild' => false
                    )
                )
            ),
            array(
                'result' => false
            )
        );

        $data[] = array(
            array(
                'mock' => array(
                    'table' => array(
                        'isLeaf'  => false,
                        'isRoot'  => false,
                        'isChild' => true
                    ),
                    'other' => array(
                        'isLeaf'  => false,
                        'isRoot'  => true,
                        'isChild' => false
                    )
                )
            ),
            array(
                'result' => false
            )
        );

        return $data;
    }

    public static function getTestScopeImmediateDescendants()
    {
        $data[] = array(
            array(
                'loadid' => 5
            ),
            array(
                'result' => array(7,9)
            )
        );

        $data[] = array(
            array(
                'loadid' => 8
            ),
            array(
                'result' => array(0)
            )
        );

        $data[] = array(
            array(
                'loadid' => 2
            ),
            array(
                'result' => array(3, 5)
            )
        );

        return $data;
    }

    public static function getTestRoot()
    {
        // The node is a root himself
        $data[] = array(
            array(
                'loadid'  => 1,
                'cache'   => null,
                'newRoot' => false
            ),
            array(
                'result' => 1
            )
        );

        // Two roots - The node is a root himself
        $data[] = array(
            array(
                'loadid'  => 17,
                'cache'   => null,
                'newRoot' => true
            ),
            array(
                'result' => 17
            )
        );

        // Single root - The root is the immediate parent of the node
        $data[] = array(
            array(
                'loadid'  => 2,
                'cache'   => null,
                'newRoot' => false
            ),
            array(
                'result' => 1
            )
        );

        // Single root -  Node is deeper than first level
        $data[] = array(
            array(
                'loadid'  => 7,
                'cache'   => null,
                'newRoot' => false
            ),
            array(
                'result' => 1
            )
        );

        // Two roots - Node is deeper than first level
        $data[] = array(
            array(
                'loadid'  => 7,
                'cache'   => null,
                'newRoot' => true
            ),
            array(
                'result' => 1
            )
        );

        // The treeRoot is cached - wrong way
        $data[] = array(
            array(
                'loadid'  => 2,
                'cache'   => 1,
                'newRoot' => false
            ),
            array(
                'result' => 1
            )
        );

        // The treeRoot is cached - wrong way 2
        $data[] = array(
            array(
                'loadid'  => 2,
                'cache'   => new stdClass(),
                'newRoot' => false
            ),
            array(
                'result' => 1
            )
        );

        // The treeRoot is cached - right way
        $data[] = array(
            array(
                'loadid'  => 2,
                'cache'   => 'loadself',
                'newRoot' => false
            ),
            array(
                'result' => 2
            )
        );

        // Two roots - Your root is in another castle
        $data[] = array(
            array(
                'loadid'  => 20,
                'cache'   => null,
                'newRoot' => true
            ),
            array(
                'result' => 17
            )
        );

        // Two roots - Your root is in another castle
        $data[] = array(
            array(
                'loadid'  => 18,
                'cache'   => null,
                'newRoot' => true
            ),
            array(
                'result' => 17
            )
        );

        return $data;
    }

    public static function getTestRootException()
    {
        // Node is not loaded
        $data[] = array(
            array(
                'loadid' => 0,
                'mock'   => array(
                    'current' => array(false, false)
                ),
                'wrongNode' => false
            )
        );

        // Can't find any valid root
        $data[] = array(
            array(
                'loadid' => 2,
                'mock'   => array(
                    'current' => array(true, false)
                ),
                'wrongNode' => false
            )
        );

        // There 2 roots, I get an error while trying to load all the roots
        $data[] = array(
            array(
                'loadid' => 2,
                'mock'   => array(
                    'current' => array(false, true)
                ),
                'wrongNode' => true
            )
        );

        // There 2 roots, I get an error while getting the correct one
        $data[] = array(
            array(
                'loadid' => 2,
                'mock'   => array(
                    'current' => array(false, true)
                ),
                'wrongNode' => false
            )
        );

        return $data;
    }
}
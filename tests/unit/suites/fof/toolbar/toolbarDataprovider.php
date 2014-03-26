<?php

class ToolbarDataprovider
{
    public static function getTestOnCpanelsBrowse()
    {
        $data[] = array(
            array(
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTESTS', 'foftests')),
                    'preferences' => array(array('com_foftests', 550, 875))
                )
            )
        );

        $data[] = array(
            array(
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => false
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTESTS', 'foftests')),
                    'preferences' => array(array('com_foftests', 550, 875))
                )
            )
        );

        // Submenu should not be called
        $data[] = array(
            array(
                'isBackend'   => false,
                'submenu'     => false,
                'callSubmenu' => false,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTESTS', 'foftests')),
                    'preferences' => array(array('com_foftests', 550, 875))
                )
            )
        );

        $data[] = array(
            array(
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTESTS', 'foftests')),
                    'preferences' => array(array('com_foftests', 550, 875))
                )
            )
        );

        $data[] = array(
            array(
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => false
            ),
            array(
                'methods' => array()
            )
        );

        return $data;
    }

    public static function getTestOnBrowse()
    {
        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_CPANEL', 'foftests')),
                    'addNew'        => array(array()),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTESTS_CONFIRM_DELETE')),
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBAR', 'foftests')),
                    'addNew'        => array(array()),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTESTS_CONFIRM_DELETE')),
                )
            )
        );

        // No permissions to create
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBAR', 'foftests')),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTESTS_CONFIRM_DELETE')),
                )
            )
        );

        // Create but no edit
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => false,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBAR', 'foftests')),
                    'addNew'        => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTESTS_CONFIRM_DELETE')),
                )
            )
        );

        // No create, no edit
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => false,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBAR', 'foftests')),
                    'divider'       => array(array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTESTS_CONFIRM_DELETE')),
                )
            )
        );

        // No create, no edit, no edistate
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => false,
                    'editstate' => false,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBAR', 'foftests')),
                    'deleteList'    => array(array('COM_FOFTESTS_CONFIRM_DELETE')),
                )
            )
        );

        // No permissions
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => false,
                    'editstate' => false,
                    'delete'    => false
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBAR', 'foftests'))
                )
            )
        );

        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => false,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_CPANEL', 'foftests')),
                    'addNew'        => array(array()),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTESTS_CONFIRM_DELETE')),
                )
            )
        );

        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => false,
                'submenu'     => false,
                'callSubmenu' => false,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_CPANEL', 'foftests')),
                    'addNew'        => array(array()),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTESTS_CONFIRM_DELETE')),
                )
            )
        );

        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editstate' => true,
                    'delete'    => true
                )
            ),
            array(
                'methods' => array(
                    'title'         => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_CPANEL', 'foftests')),
                    'addNew'        => array(array()),
                    'editList'      => array(array()),
                    'divider'       => array(array(), array()),
                    'publishList'   => array(array()),
                    'unpublishList' => array(array()),
                    'deleteList'    => array(array('COM_FOFTESTS_CONFIRM_DELETE')),
                )
            )
        );

        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => false,
                'perms'       => array()
            ),
            array(
                'methods' => array()
            )
        );

        return $data;
    }
}
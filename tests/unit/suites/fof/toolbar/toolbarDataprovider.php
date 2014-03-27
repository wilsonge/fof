<?php

use org\bovigo\vfs\vfsStream;

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

    public static function getTestOnRead()
    {
        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_CPANEL_READ', 'foftests')),
                    'back'  => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'submenu'     => false,
                'callSubmenu' => true,
                'buttons'     => false
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBAR_READ', 'foftests')),
                    'back'  => array(array())
                )
            )
        );

        // Submenu should not be called
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => false,
                'submenu'     => false,
                'callSubmenu' => false,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBAR_READ', 'foftests')),
                    'back'  => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => false,
                'submenu'     => true,
                'callSubmenu' => true,
                'buttons'     => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBAR_READ', 'foftests')),
                    'back'  => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
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

    public static function getTestOnAdd()
    {
        // With default view
        $data[] = array(
            array(
                'view'        => null,
                'isBackend'   => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_CPANELS_EDIT', 'foftests')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'custom'   => array(array('savenew', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false)),
                    'cancel'   => array(array())
                )
            )
        );

        // Passing a view
        // Start testing for backend/showbuttons combinations
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBARS_EDIT', 'foftests')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'custom'   => array(array('savenew', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false)),
                    'cancel'   => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => false,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBARS_EDIT', 'foftests')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'custom'   => array(array('savenew', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false)),
                    'cancel'   => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'buttons'     => false,
                'perms'       => array(
                    'create'    => true,
                    'edit'      => true,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBARS_EDIT', 'foftests')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'custom'   => array(array('savenew', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false)),
                    'cancel'   => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => false,
                'buttons'     => false,
                'perms'       => array()
            ),
            array(
                'methods' => array()
            )
        );

        // End testing for backend/showbuttons combinations

        // Start testing with different permissions
        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => true,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBARS_EDIT', 'foftests')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'cancel'   => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => false,
                    'editown'   => false
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBARS_EDIT', 'foftests')),
                    'save'     => array(array()),
                    'cancel'   => array(array())
                )
            )
        );

        $data[] = array(
            array(
                'view'        => 'foobar',
                'isBackend'   => true,
                'buttons'     => true,
                'perms'       => array(
                    'create'    => false,
                    'edit'      => false,
                    'editown'   => true
                )
            ),
            array(
                'methods' => array(
                    'title'    => array(array('COM_FOFTESTS: COM_FOFTESTS_TITLE_FOOBARS_EDIT', 'foftests')),
                    'apply'    => array(array()),
                    'save'     => array(array()),
                    'cancel'   => array(array())
                )
            )
        );

        // End testing with different permissions

        return $data;
    }

    public static function getTestGetMyViews()
    {
	    $origpaths = array(
		    'administrator/components/com_foftest/views/bare',
		    'administrator/components/com_foftest/views/bares',
		    'administrator/components/com_foftest/views/foobar',
		    'administrator/components/com_foftest/views/foobars'
	    );

	    foreach($origpaths as &$path)
	    {
		    $path  = trim(str_replace('\\', '/', $path), '/');
	    }

	    // Standard folders, no cpanel view
	    $paths = $origpaths;
        $data[] = array(
            array(
                'structure' => self::createArrayDir($paths),
                'folders'   => array(
	                'bare',
	                'bares',
	                'foobar',
	                'foobars'
                )
            ),
	        array(
		        'views' => array(
			        'bares',
			        'foobars'
		        )
	        )
        );

	    // If cpanel is here, it should be the first one
        $paths = $origpaths;
	    $data[] = array(
		    array(
                'structure' => self::createArrayDir($paths),
			    'folders' => array(
				    'bare',
				    'bares',
				    'cpanel',
				    'foobar',
				    'foobars',
			    )
		    ),
		    array(
			    'views' => array(
				    'cpanels',
				    'bares',
				    'foobars'
			    )
		    )
	    );

        return $data;
    }

    protected static function createArrayDir($paths)
    {
        $tree = array();
        foreach ($paths as $path) {
            $pathParts = explode('/', $path);
            $subTree   = array(array_pop($pathParts));

            if(strpos($subTree[0], '.') !== false)
            {
                $subTree = array($subTree[0] => 'dummy');
            }
	        else
	        {
		        $subTree = array($subTree[0] => array());
	        }

            foreach (array_reverse($pathParts) as $dir) {
                $subTree = array($dir => $subTree);
            }
            $tree = array_merge_recursive($tree, $subTree);
        }

        return $tree;
    }
}
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
}
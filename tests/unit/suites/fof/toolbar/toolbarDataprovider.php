<?php

class ToolbarDataprovider
{
    public static function getTestOnCpanelsBrowse()
    {
        $data[] = array(
            array(
                'isBackend' => true,
                'submenu'   => false,
                'buttons'   => true
            ),
            array(
                'methods' => array(
                    'title' => array(array('COM_FOFTESTS', 'foftests')),
                    'preferences' => array(array('com_foftests', 550, 875))
                )
            )
        );

        return $data;
    }
}
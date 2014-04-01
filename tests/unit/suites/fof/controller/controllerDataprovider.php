<?php

class ControllerDataprovider
{
    public static function getTestCreateFilename()
    {
        $data[] = array(
            array(
                'type'  => 'controller',
                'parts' => array(
                    'name' => 'foobar'
                )
            ),
            array(
                'filename' => 'foobar.php'
            )
        );

        $data[] = array(
            array(
                'type'  => 'controller',
                'parts' => array(
                    'name'   => 'foobar',
                    'format' => 'html'
                )
            ),
            array(
                'filename' => 'foobar.php'
            )
        );

        $data[] = array(
            array(
                'type'  => 'controller',
                'parts' => array(
                    'name'   => 'foobar',
                    'format' => 'raw'
                )
            ),
            array(
                'filename' => 'foobar.raw.php'
            )
        );

        $data[] = array(
            array(
                'type'  => 'view',
                'parts' => array(
                    'name' => 'foobar',
                    'type' => 'html'
                )
            ),
            array(
                'filename' => 'foobar/view.html.php'
            )
        );

        $data[] = array(
            array(
                'type'  => 'view',
                'parts' => array(
                    'name' => 'foobar',
                    'type' => 'raw'
                )
            ),
            array(
                'filename' => 'foobar/view.raw.php'
            )
        );

        return $data;
    }

    public static function getTestBrowse()
    {
        $data[] = array(
            array(
                'cache'  => array('browse', 'read'),
                'layout' => ''
            ),
            array(
                'cache'     => true,
                'form_name' => 'form.default'
            )
        );

        $data[] = array(
            array(
                'cache'  => array('browse', 'read'),
                'layout' => 'dummy'
            ),
            array(
                'cache'     => true,
                'form_name' => 'form.dummy'
            )
        );

        $data[] = array(
            array(
                'cache'  => array('read'),
                'layout' => ''
            ),
            array(
                'cache'     => false,
                'form_name' => 'form.default'
            )
        );

        return $data;
    }
}
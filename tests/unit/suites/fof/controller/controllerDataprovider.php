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
                'form_name' => 'form.default',
                'return'    => true
            )
        );

        $data[] = array(
            array(
                'cache'  => array('browse', 'read'),
                'layout' => 'dummy'
            ),
            array(
                'cache'     => true,
                'form_name' => 'form.dummy',
                'return'    => true
            )
        );

        $data[] = array(
            array(
                'cache'  => array('read'),
                'layout' => ''
            ),
            array(
                'cache'     => false,
                'form_name' => 'form.default',
                'return'    => true
            )
        );

        return $data;
    }

    public static function getTestRead()
    {
        $item = FOFTable::getAnInstance('Foobar', 'FoftestTable');
        $data[] = array(
            array(
                'cache'  => array('browse', 'read'),
                'layout' => null,
                'id'     => 2,
                'item'   => $item,
                'loadid' => 2
            ),
            array(
                'cache'     => true,
                'form_name' => 'form.item',
                'return'    => true
            )
        );

        $data[] = array(
            array(
                'cache'  => array('browse', 'read'),
                'layout' => 'dummy',
                'id'     => 2,
                'item'   => $item,
                'loadid' => 2
            ),
            array(
                'cache'     => true,
                'form_name' => 'form.dummy',
                'return'    => true
            )
        );

        $data[] = array(
            array(
                'cache'  => array('browse'),
                'layout' => null,
                'id'     => 2,
                'item'   => $item,
                'loadid' => 2
            ),
            array(
                'cache'     => false,
                'form_name' => 'form.item',
                'return'    => true
            )
        );

        $data[] = array(
            array(
                'cache'  => array('browse', 'read'),
                'layout' => null,
                'id'     => 2,
                'item'   => $item,
                'loadid' => 3
            ),
            array(
                'cache'     => true,
                'form_name' => 'form.item',
                'return'    => false
            )
        );

        $data[] = array(
            array(
                'cache'  => array('browse', 'read'),
                'layout' => null,
                'id'     => 2,
                'item'   => new stdClass(),
                'loadid' => 0
            ),
            array(
                'cache'     => true,
                'form_name' => 'form.item',
                'return'    => false
            )
        );

        return $data;
    }
}
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
                    'format' => 'raw'
                )
            ),
            array(
                'filename' => 'foobar.raw.php'
            )
        );

        return $data;
    }
}
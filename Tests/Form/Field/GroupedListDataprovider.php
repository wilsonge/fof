<?php

class GroupedListDataprovider
{
    public static function getTest__get()
    {
        $data[] = array(
            'input' => array(
                'property' => 'static',
                'static'   => null,
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the static method, not cached',
                'static' => 1,
                'repeat' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'static',
                'static'   => 'cached',
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the static method, cached',
                'static' => 0,
                'repeat' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'repeatable',
                'static'   => null,
                'repeat'   => null
            ),
            'check' => array(
                'case'   => 'Requesting for the repeatable method, not cached',
                'static' => 0,
                'repeat' => 1
            )
        );

        $data[] = array(
            'input' => array(
                'property' => 'repeatable',
                'static'   => null,
                'repeat'   => 'cached'
            ),
            'check' => array(
                'case'   => 'Requesting for the repeatable method, cached',
                'static' => 0,
                'repeat' => 0
            )
        );

        return $data;
    }

    public static function getTestGetStatic()
    {
        $data[] = array(
            'input' => array(
                'legacy' => true
            ),
            'check' => array(
                'case'     => 'Using the legacy attribute',
                'input'    => 1,
                'contents' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'legacy' => false
            ),
            'check' => array(
                'case'     => 'Without using the legacy attribute',
                'input'    => 0,
                'contents' => 1
            )
        );

        return $data;
    }

    public static function getTestGetRepeatable()
    {
        $data[] = array(
            'input' => array(
                'legacy' => true
            ),
            'check' => array(
                'case'     => 'Using the legacy attribute',
                'input'    => 1,
                'contents' => 0
            )
        );

        $data[] = array(
            'input' => array(
                'legacy' => false
            ),
            'check' => array(
                'case'     => 'Without using the legacy attribute',
                'input'    => 0,
                'contents' => 1
            )
        );

        return $data;
    }

    public static function getTestGetFieldContents()
    {
        $data[] = array(
            'input' => array(
                'value'   => '3',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'Value found, no additional options',
                'result' => '<span class="fof-groupedlist-group">Group 2</span><span class="fof-groupedlist-item">Option 3</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'value'   => '3',
                'options' => array(
                    'id' => 'foo-id',
                    'class' => 'foo-class'
                )
            ),
            'check' => array(
                'case'   => 'Value found, with additional options',
                'result' => '<span id="foo-id-group" class="fof-groupedlist-group foo-class">Group 2</span><span id="foo-id-item" class="fof-groupedlist-item foo-class">Option 3</span>'
            )
        );

        $data[] = array(
            'input' => array(
                'value'   => '100',
                'options' => array()
            ),
            'check' => array(
                'case'   => 'Value not found',
                'result' => '<span class="fof-groupedlist-group"></span><span class="fof-groupedlist-item"></span>'
            )
        );

        return $data;
    }
}
